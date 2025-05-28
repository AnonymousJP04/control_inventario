<?php

namespace App\Http\Controllers;

use App\Models\DetalleVenta;
use App\Models\Producto; // Necesario para obtener precio si no se envía
use App\Models\Venta;   // Necesario para recalcular total de venta
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB; // Para transacciones si se maneja stock aquí

class DetalleVentaController extends Controller
{
    // Listar detalles, opcionalmente filtrados por venta_id
    public function index(Request $request)
    {
        $query = DetalleVenta::with(['producto', 'user']);
        if ($request->has('venta_id')) {
            $query->where('venta_id', $request->venta_id);
            $detalles = $query->get();
        } else {
            $detalles = $query->latest()->paginate(15);
        }
        return response()->json($detalles);
    }

    // Crear un nuevo detalle de venta (ej. para añadir a una venta existente vía AJAX)
    // NOTA: La actualización de stock y total de la Venta principal debería manejarse
    // de forma centralizada, idealmente en VentaController o un servicio.
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'venta_id' => 'required|exists:ventas,id',
            'producto_id' => 'required|exists:productos,id',
            'cantidad' => 'required|integer|min:1',
            'precio_unitario' => 'sometimes|required|numeric|min:0',
            'descuento' => 'nullable|numeric|min:0',
        ]);

        $producto = Producto::findOrFail($validatedData['producto_id']);
        $precioUnitario = $validatedData['precio_unitario'] ?? $producto->precio;
        $subtotal = $validatedData['cantidad'] * $precioUnitario;
        $descuento = $validatedData['descuento'] ?? 0;
        $totalItem = $subtotal - $descuento;

        // Considerar validación de stock aquí también si este endpoint puede afectar el stock directamente
        // if ($producto->stockActual() < $validatedData['cantidad']) { ... }

        $detalleVenta = DetalleVenta::create([
            'venta_id' => $validatedData['venta_id'],
            'producto_id' => $validatedData['producto_id'],
            'cantidad' => $validatedData['cantidad'],
            'precio_unitario' => $precioUnitario,
            'subtotal' => $subtotal,
            'descuento' => $descuento,
            'total' => $totalItem,
            'user_id' => Auth::id(),
        ]);

        // Disparar evento o llamar a servicio para actualizar Venta->total y Producto->stock
        // Ejemplo simple (no ideal para producción sin más estructura):
        // $this->recalculateVentaTotal($detalleVenta->venta_id);

        return response()->json($detalleVenta, 201);
    }

    public function show(DetalleVenta $detalleVenta)
    {
        $detalleVenta->load(['venta', 'producto', 'user']);
        return response()->json($detalleVenta);
    }

    // Actualizar un detalle de venta existente
    public function update(Request $request, DetalleVenta $detalleVenta)
    {
        $validatedData = $request->validate([
            'cantidad' => 'sometimes|required|integer|min:1',
            'precio_unitario' => 'sometimes|required|numeric|min:0',
            'descuento' => 'nullable|numeric|min:0',
        ]);

        $dataToUpdate = [];
        // ... (lógica de preparación de $dataToUpdate como la tenías) ...
        if (isset($validatedData['cantidad'])) $dataToUpdate['cantidad'] = $validatedData['cantidad'];
        if (isset($validatedData['precio_unitario'])) $dataToUpdate['precio_unitario'] = $validatedData['precio_unitario'];
        if (array_key_exists('descuento', $validatedData)) $dataToUpdate['descuento'] = $validatedData['descuento'] ?? 0;


        if (isset($dataToUpdate['cantidad']) || isset($dataToUpdate['precio_unitario'])) {
            $cantidad = $dataToUpdate['cantidad'] ?? $detalleVenta->cantidad;
            $precioUnitario = $dataToUpdate['precio_unitario'] ?? $detalleVenta->precio_unitario;
            $dataToUpdate['subtotal'] = $cantidad * $precioUnitario;
            $descuento = $dataToUpdate['descuento'] ?? $detalleVenta->descuento;
            $dataToUpdate['total'] = $dataToUpdate['subtotal'] - $descuento;
        } elseif (isset($dataToUpdate['descuento'])) {
             $dataToUpdate['total'] = $detalleVenta->subtotal - $dataToUpdate['descuento'];
        }


        if (!empty($dataToUpdate)) {
            // Considerar validación de stock y ajuste de stock aquí si este endpoint lo maneja
            $detalleVenta->update($dataToUpdate);
        }

        // Disparar evento o llamar a servicio para actualizar Venta->total y Producto->stock
        // $this->recalculateVentaTotal($detalleVenta->venta_id);

        return response()->json($detalleVenta);
    }

    // Eliminar un detalle de venta
    public function destroy(DetalleVenta $detalleVenta)
    {
        // Considerar ajuste de stock aquí si este endpoint lo maneja
        $detalleVenta->delete();

        // Disparar evento o llamar a servicio para actualizar Venta->total y Producto->stock
        // $this->recalculateVentaTotal($ventaId);

        return response()->json(null, 204);
    }

    // Helper (podría estar en un servicio)
    // protected function recalculateVentaTotal($ventaId)
    // {
    //     $venta = Venta::find($ventaId);
    //     if ($venta) {
    //         $venta->total = $venta->detalles()->sum('total');
    //         $venta->save();
    //     }
    // }
}
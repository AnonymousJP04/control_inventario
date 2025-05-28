<?php

namespace App\Http\Controllers;

use App\Models\DetalleVenta;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DetalleVentaController extends Controller
{
    public function index(Request $request)
    {
        if ($request->has('venta_id')) {
            $detalles = DetalleVenta::where('venta_id', $request->venta_id)
                                    ->with(['producto', 'user'])
                                    ->get();
        } else {
            $detalles = DetalleVenta::with(['venta', 'producto', 'user'])->latest()->paginate(15);
        }
        return response()->json($detalles);
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'venta_id' => 'required|exists:ventas,id',
            'producto_id' => 'required|exists:productos,id',
            'cantidad' => 'required|integer|min:1',
            'precio_unitario' => 'required|numeric|min:0',
            'descuento' => 'nullable|numeric|min:0',
        ]);

        $subtotal = $validatedData['cantidad'] * $validatedData['precio_unitario'];
        $descuento = $validatedData['descuento'] ?? 0;
        $totalItem = $subtotal - $descuento;

        $detalleVenta = DetalleVenta::create([
            'venta_id' => $validatedData['venta_id'],
            'producto_id' => $validatedData['producto_id'],
            'cantidad' => $validatedData['cantidad'],
            'precio_unitario' => $validatedData['precio_unitario'],
            'subtotal' => $subtotal,
            'descuento' => $descuento,
            'total' => $totalItem,
            'user_id' => Auth::id(),
        ]);
        // Recordatorio: Actualizar Venta->total y Producto->stock aquí o en un servicio.
        return response()->json($detalleVenta, 201);
    }

    public function show(DetalleVenta $detalleVenta)
    {
        $detalleVenta->load(['venta', 'producto', 'user']);
        return response()->json($detalleVenta);
    }

    public function update(Request $request, DetalleVenta $detalleVenta)
    {
        $validatedData = $request->validate([
            'cantidad' => 'sometimes|required|integer|min:1',
            'precio_unitario' => 'sometimes|required|numeric|min:0',
            'descuento' => 'nullable|numeric|min:0',
        ]);

        $dataToUpdate = [];
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
            $detalleVenta->update($dataToUpdate);
        }
        // Recordatorio: Actualizar Venta->total y Producto->stock aquí o en un servicio.
        return response()->json($detalleVenta);
    }

    public function destroy(DetalleVenta $detalleVenta)
    {
        $detalleVenta->delete();
        // Recordatorio: Actualizar Venta->total y Producto->stock aquí o en un servicio.
        return response()->json(null, 204);
    }
}
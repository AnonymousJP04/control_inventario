<?php

namespace App\Http\Controllers;

use App\Models\Venta;
use App\Models\Cliente;
use App\Models\Producto;
use App\Models\DetalleVenta;
use App\Models\StockSalida;
use App\Models\StockEntrada; // Para cancelaciones/devoluciones
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class VentaController extends Controller
{
    public function index()
    {
        $ventas = Venta::with(['cliente', 'user', 'detalles.producto'])->latest()->paginate(10);
        return view('ventas.index', compact('ventas')); // Asegúrate de tener esta vista
    }

    public function create()
    {
        $clientes = Cliente::orderBy('nombre')->get();
        $productos = Producto::orderBy('nombre')->get(); // Productos para seleccionar
        return view('ventas.create', compact('clientes', 'productos')); // Asegúrate de tener esta vista
    }

    public function store(Request $request)
    {
        $request->validate([
            'cliente_id' => 'required|exists:clientes,id',
            'detalles_venta' => 'required|array|min:1',
            'detalles_venta.*.producto_id' => 'required|exists:productos,id',
            'detalles_venta.*.cantidad' => 'required|integer|min:1',
            'detalles_venta.*.precio_unitario' => 'sometimes|required|numeric|min:0', // Opcional si se toma del producto
            'detalles_venta.*.descuento' => 'nullable|numeric|min:0',
        ]);

        DB::beginTransaction();
        try {
            $venta = Venta::create([
                'cliente_id' => $request->cliente_id,
                'user_id' => Auth::id(),
                'total' => 0, // Se calculará después
            ]);

            $ventaTotalCalculado = 0;

            foreach ($request->detalles_venta as $item) {
                $producto = Producto::findOrFail($item['producto_id']);
                $cantidad = (int)$item['cantidad'];
                $precioUnitario = $item['precio_unitario'] ?? $producto->precio; // Usar precio del producto si no se envía
                $descuentoItem = $item['descuento'] ?? 0;

                // Verificar stock
                if ($producto->stockActual() < $cantidad) {
                    throw ValidationException::withMessages([
                        'detalles_venta' => "Stock insuficiente para el producto: {$producto->nombre}. Disponible: {$producto->stockActual()}"
                    ]);
                }

                $subtotalItem = $cantidad * $precioUnitario;
                $totalItem = $subtotalItem - $descuentoItem;
                $ventaTotalCalculado += $totalItem;

                DetalleVenta::create([
                    'venta_id' => $venta->id,
                    'producto_id' => $producto->id,
                    'cantidad' => $cantidad,
                    'precio_unitario' => $precioUnitario,
                    'subtotal' => $subtotalItem,
                    'descuento' => $descuentoItem,
                    'total' => $totalItem,
                    'user_id' => Auth::id(),
                ]);

                // Registrar salida de stock
                StockSalida::create([
                    'producto_id' => $producto->id,
                    'user_id' => Auth::id(),
                    'cantidad' => $cantidad,
                    'motivo' => 'Venta ID: ' . $venta->id,
                    'fecha' => now(),
                ]);
            }

            $venta->total = $ventaTotalCalculado;
            $venta->save();

            DB::commit();
            return redirect()->route('ventas.index')->with('success', 'Venta creada exitosamente.');

        } catch (\Exception $e) {
            DB::rollBack();
            // \Log::error('Error al crear venta: ' . $e->getMessage() . $e->getTraceAsString()); // Para depuración
            $errorMessage = $e instanceof ValidationException ? $e->getMessage() : 'Ocurrió un error al procesar la venta.';
            if (!($e instanceof ValidationException)) {
                 $errorMessage .= ' Detalles: ' . $e->getMessage();
            }
            return back()->withErrors(['error_inesperado' => $errorMessage])->withInput();
        }
    }

    public function show(Venta $venta)
    {
        $venta->load(['cliente', 'user', 'detalles.producto']);
        return view('ventas.show', compact('venta')); // Asegúrate de tener esta vista
    }

    // La edición de ventas con detalles y stock es compleja.
    // Esta es una implementación simplificada. Una real podría implicar
    // comparar arrays de detalles, revertir stock, etc.
    public function edit(Venta $venta)
    {
        $venta->load('detalles.producto');
        $clientes = Cliente::orderBy('nombre')->get();
        $productos = Producto::orderBy('nombre')->get();
        return view('ventas.edit', compact('venta', 'clientes', 'productos')); // Asegúrate de tener esta vista
    }

    public function update(Request $request, Venta $venta)
    {
        // Actualizar una venta con detalles y stock es una operación compleja.
        // Implica:
        // 1. Revertir stock de detalles eliminados/modificados.
        // 2. Actualizar detalles existentes.
        // 3. Añadir nuevos detalles y descontar su stock.
        // 4. Recalcular el total de la venta.
        // Por simplicidad, esta función podría solo permitir cambiar el cliente
        // o requerir una reimplementación completa de la lógica de detalles.
        // Aquí un ejemplo muy básico que solo actualiza el cliente.
        $request->validate([
            'cliente_id' => 'required|exists:clientes,id',
            // Aquí iría la validación para 'detalles_venta' si se permite su modificación completa.
        ]);

        DB::beginTransaction();
        try {
            // Lógica para actualizar detalles (simplificado - solo cliente por ahora)
            // Para una actualización completa de detalles, necesitarías una lógica similar a `store`
            // pero manejando también los detalles existentes (actualizar, eliminar) y revertir/aplicar stock.

            $venta->cliente_id = $request->cliente_id;
            // Si se modifican detalles, recalcular $venta->total
            $venta->save();

            DB::commit();
            return redirect()->route('ventas.index')->with('success', 'Venta actualizada (cliente).');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error_inesperado' => 'Error al actualizar la venta: ' . $e->getMessage()])->withInput();
        }
    }


    public function destroy(Venta $venta)
    {
        DB::beginTransaction();
        try {
            // Revertir stock por cada detalle de la venta
            foreach ($venta->detalles as $detalle) {
                StockEntrada::create([
                    'producto_id' => $detalle->producto_id,
                    'user_id' => Auth::id(), // O un usuario específico de sistema para cancelaciones
                    'cantidad' => $detalle->cantidad,
                    'motivo' => 'Cancelación Venta ID: ' . $venta->id,
                    'fecha' => now(),
                ]);
            }

            // Los detalles se eliminarán por cascade si la FK está configurada así en la migración.
            // Si no, eliminarlos manualmente: $venta->detalles()->delete();
            $venta->delete();

            DB::commit();
            return redirect()->route('ventas.index')->with('success', 'Venta eliminada y stock revertido.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error_inesperado' => 'Error al eliminar la venta: ' . $e->getMessage()])->withInput();
        }
    }
}
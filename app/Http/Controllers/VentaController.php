<?php

namespace App\Http\Controllers;

use App\Models\Venta;
use App\Models\Cliente; // Para el formulario de creación
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB; // Para transacciones

class VentaController extends Controller
{
    public function index()
    {
        $ventas = Venta::with(['cliente', 'user'])->latest()->paginate(10);
        return view('ventas.index', compact('ventas'));
    }

    public function create()
    {
        $clientes = Cliente::orderBy('nombre')->get();
        return view('ventas.create', compact('clientes'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'cliente_id' => 'required|exists:clientes,id',
            'total' => 'required|numeric|min:0',
            // Si tuvieras detalles:
            // 'detalles.*.producto_id' => 'required|exists:productos,id',
            // 'detalles.*.cantidad' => 'required|integer|min:1',
        ]);

        DB::beginTransaction();
        try {
            $venta = Venta::create([
                'cliente_id' => $request->cliente_id,
                'user_id' => Auth::id(),
                'total' => $request->total,
            ]);

            // Aquí iría la lógica para crear DetalleVenta y actualizar stock
            // foreach ($request->detalles as $detalle) { ... }

            DB::commit();
            return redirect()->route('ventas.index')->with('success', 'Venta creada.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Error al crear la venta: ' . $e->getMessage()])->withInput();
        }
    }

    public function show(Venta $venta)
    {
        $venta->load(['cliente', 'user', 'detalles']); // 'detalles' si existe la relación y tabla
        return view('ventas.show', compact('venta'));
    }

    public function edit(Venta $venta)
    {
        $clientes = Cliente::orderBy('nombre')->get();
        // $venta->load('detalles'); // Para editar detalles
        return view('ventas.edit', compact('venta', 'clientes'));
    }

    public function update(Request $request, Venta $venta)
    {
        $request->validate([
            'cliente_id' => 'required|exists:clientes,id',
            'total' => 'required|numeric|min:0',
        ]);

        DB::beginTransaction();
        try {
            $venta->update($request->only(['cliente_id', 'total']));

            // Aquí iría la lógica para actualizar DetalleVenta y stock
            DB::commit();
            return redirect()->route('ventas.index')->with('success', 'Venta actualizada.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Error al actualizar la venta: ' . $e->getMessage()])->withInput();
        }
    }

    public function destroy(Venta $venta)
    {
        DB::beginTransaction();
        try {
            // Aquí iría la lógica para revertir stock y eliminar DetalleVenta
            // $venta->detalles()->delete();
            $venta->delete();
            DB::commit();
            return redirect()->route('ventas.index')->with('success', 'Venta eliminada.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Error al eliminar la venta: ' . $e->getMessage()])->withInput();
        }
    }
}
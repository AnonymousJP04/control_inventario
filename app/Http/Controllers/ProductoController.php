<?php

namespace App\Http\Controllers;

use App\Models\Producto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProductoController extends Controller
{
    /**
     * Display a listing of the products.
     */
    public function index()
    {
        // Paginar resultados para mejor rendimiento
        $productos = Producto::with('user')->latest()->paginate(10);
        return view('productos.index', compact('productos')); // Asegúrate de tener esta vista
    }

    /**
     * Show the form for creating a new product.
     */
    public function create()
    {
        return view('productos.create'); // Asegúrate de tener esta vista
    }

    /**
     * Store a newly created product in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255|unique:productos,nombre',
            'descripcion' => 'nullable|string',
            'precio' => 'required|numeric|min:0',
            'stock_minimo' => 'required|integer|min:0',
        ]);

        Producto::create([
            'nombre' => $request->nombre,
            'descripcion' => $request->descripcion,
            'precio' => $request->precio,
            'stock_minimo' => $request->stock_minimo,
            'user_id' => Auth::id(),
        ]);

        return redirect()->route('productos.index')
                         ->with('success', 'Producto creado exitosamente.');
    }

    /**
     * Display the specified product.
     */
    public function show(Producto $producto)
    {
        // Cargar relaciones si es necesario para la vista
        $producto->load(['user', 'stockEntradas', 'stockSalidas']);
        $stockActual = $producto->stockActual(); // Calcular stock actual
        return view('productos.show', compact('producto', 'stockActual')); // Asegúrate de tener esta vista
    }

    /**
     * Show the form for editing the specified product.
     */
    public function edit(Producto $producto)
    {
        return view('productos.edit', compact('producto')); // Asegúrate de tener esta vista
    }

    /**
     * Update the specified product in storage.
     */
    public function update(Request $request, Producto $producto)
    {
        $request->validate([
            'nombre' => 'required|string|max:255|unique:productos,nombre,' . $producto->id,
            'descripcion' => 'nullable|string',
            'precio' => 'required|numeric|min:0',
            'stock_minimo' => 'required|integer|min:0',
        ]);

        $producto->update($request->all());

        return redirect()->route('productos.index')
                         ->with('success', 'Producto actualizado exitosamente.');
    }

    /**
     * Remove the specified product from storage.
     */
    public function destroy(Producto $producto)
    {
        // Considerar lógica adicional: no permitir eliminar si tiene stock o está en ventas activas.
        // Por ahora, eliminación simple.
        try {
            $producto->delete();
            return redirect()->route('productos.index')
                             ->with('success', 'Producto eliminado exitosamente.');
        } catch (\Illuminate\Database\QueryException $e) {
            // Manejar error si hay restricciones de clave foránea (ej. si está en detalle_ventas y no hay onDelete('cascade'))
            return redirect()->route('productos.index')
                             ->with('error', 'No se pudo eliminar el producto. Puede estar asociado a ventas u otros registros.');
        }
    }
}
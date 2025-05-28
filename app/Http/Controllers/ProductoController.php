<?php
 
namespace App\Http\Controllers;
 
use App\Models\Producto;
use Illuminate\Http\Request;
 
 
class ProductoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $productos = Producto::all();
        return view('productos.index', compact('productos'));
    }
 
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('productos.create');
    }
 
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'precio' => 'required|numeric|min:0',
            'stock_minimo' => 'required|integer|min:0',            
        ]);
 
        Producto::create([
            'nombre' => $request->nombre,
            'descripcion' => $request->descripcion,
            'precio' => $request->precio,
            'stock_minimo' => $request->stock_minimo,
            'user_id' => request()->user()->id
        ]);
 
        return redirect()->route('productos.index')->with('success', 'Producto creado exitosamente.');
    }
 
    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }
 
    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Producto $producto)
    {
        return view('productos.edit', compact('producto'));
    }
 
    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Producto $producto)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'precio' => 'required|numeric|min:0',
            'stock_minimo' => 'required|integer|min:0',            
        ]);
 
        $producto->update([
            'nombre' => $request->nombre,
            'descripcion' => $request->descripcion,
            'precio' => $request->precio,
            'stock_minimo' => $request->stock_minimo,
            'user_id' => request()->user()->id
        ]);
 
        return redirect()->route('productos.index')->with('success', 'Producto actualizado exitosamente.');
    }
 
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Producto $producto)
    {
        $producto->delete();
        return redirect()->route('productos.index')->with('success', 'Producto eliminado exitosamente.');
    }
}
<?php
 
namespace App\Http\Controllers;
 
use Illuminate\Http\Request;
use App\Models\Producto;
 
class MovimientoController extends Controller
{
    
    public function index(Request $request)
    {
        
        $productos = Producto::with(['stockEntradas', 'stockSalidas'])->get();
        
        return view('stock.index', compact('productos'));
    }
 
    public function registrarEntrada(Request $request)
    {
        $request->validate([
            'producto_id' => 'required|exists:productos,id',
            'cantidad' => 'required|integer|min:1',
        ]);
 
        $producto = Producto::findOrFail($request->producto_id);
        $producto->stockEntradas()->create([
            'cantidad' => $request->cantidad,
            'motivo' => 'Ajuste de ingreso',
            'user_id' => $request->user()->id
        ]);
 
        return redirect()->route('stock.index')->with('success', 'Entrada registrada exitosamente.');
    }
 
    public function registrarSalida(Request $request)
    {
        $request->validate([
            'producto_id' => 'required|exists:productos,id',
            'cantidad' => 'required|integer|min:1'
        ]);
 
       $producto = Producto::findOrFail($request->producto_id);
        $producto->stockSalidas()->create([
        'cantidad' => $request->cantidad,
        'motivo' => 'Ajuste de salida',
        'user_id' => $request->user()->id
]);
 
        return redirect()->route('stock.index')->with('success', 'Salida registrada exitosamente.');
    }
}
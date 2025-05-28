<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Producto;
use App\Models\StockEntrada;
use App\Models\StockSalida;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class MovimientoController extends Controller
{
    // Muestra el historial de movimientos de stock, opcionalmente filtrado por producto
    public function index(Request $request)
    {
        $productos = Producto::orderBy('nombre')->get();
        $productoSeleccionado = null;
        $movimientos = collect(); // Colección vacía por defecto

        if ($request->filled('producto_id')) {
            $productoSeleccionado = Producto::with(['stockEntradas.user', 'stockSalidas.user'])
                                          ->findOrFail($request->producto_id);

            $entradas = $productoSeleccionado->stockEntradas->map(function ($item) {
                $item->tipo = 'Entrada';
                return $item;
            });
            $salidas = $productoSeleccionado->stockSalidas->map(function ($item) {
                $item->tipo = 'Salida';
                return $item;
            });

            $movimientos = $entradas->concat($salidas)->sortByDesc('fecha'); // o sortByDesc('created_at')
        }

        return view('movimientos.index', compact('productos', 'productoSeleccionado', 'movimientos')); // Asegúrate de tener esta vista
    }

    // Muestra el formulario para registrar una entrada o salida
    public function create()
    {
        $productos = Producto::orderBy('nombre')->get();
        return view('movimientos.create', compact('productos')); // Asegúrate de tener esta vista
    }


    public function registrarEntrada(Request $request)
    {
        $request->validate([
            'producto_id' => 'required|exists:productos,id',
            'cantidad' => 'required|integer|min:1',
            'motivo' => 'nullable|string|max:255',
            'fecha' => 'nullable|date',
        ]);

        StockEntrada::create([
            'producto_id' => $request->producto_id,
            'user_id' => Auth::id(),
            'cantidad' => $request->cantidad,
            'motivo' => $request->motivo ?? 'Entrada manual',
            'fecha' => $request->fecha ?? now(),
        ]);

        return redirect()->route('movimientos.index', ['producto_id' => $request->producto_id])
                         ->with('success', 'Entrada de stock registrada exitosamente.');
    }

    public function registrarSalida(Request $request)
    {
        $request->validate([
            'producto_id' => 'required|exists:productos,id',
            'cantidad' => 'required|integer|min:1',
            'motivo' => 'nullable|string|max:255',
            'fecha' => 'nullable|date',
        ]);

        $producto = Producto::findOrFail($request->producto_id);
        $cantidadSolicitada = (int)$request->cantidad;

        // Validar stock disponible
        if ($producto->stockActual() < $cantidadSolicitada) {
            throw ValidationException::withMessages([
                'cantidad' => "Stock insuficiente para {$producto->nombre}. Disponible: {$producto->stockActual()}"
            ]);
        }

        StockSalida::create([
            'producto_id' => $request->producto_id,
            'user_id' => Auth::id(),
            'cantidad' => $cantidadSolicitada,
            'motivo' => $request->motivo ?? 'Salida manual',
            'fecha' => $request->fecha ?? now(),
        ]);

        return redirect()->route('movimientos.index', ['producto_id' => $request->producto_id])
                         ->with('success', 'Salida de stock registrada exitosamente.');
    }
}
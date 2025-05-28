<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; // Para obtener el usuario autenticado

class ClienteController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $clientes = Cliente::with('user')->latest()->paginate(10); // Carga la relación 'user' y pagina
        return view('clientes.index', compact('clientes')); // Asegúrate de tener esta vista
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('clientes.create'); // Asegúrate de tener esta vista
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'nit' => 'required|string|max:20|unique:clientes,nit',
            'telefono' => 'required|string|max:20',
            'direccion' => 'required|string|max:255',
            'correo' => 'required|email|max:255|unique:clientes,correo',
        ]);

        Cliente::create($request->all() + ['user_id' => Auth::id()]);

        return redirect()->route('clientes.index')
                         ->with('success', 'Cliente creado exitosamente.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Cliente $cliente)
    {
        $cliente->load('user'); // Carga la relación 'user' si no está cargada
        return view('clientes.show', compact('cliente')); // Asegúrate de tener esta vista
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Cliente $cliente)
    {
        return view('clientes.edit', compact('cliente')); // Asegúrate de tener esta vista
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Cliente $cliente)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'nit' => 'required|string|max:20|unique:clientes,nit,' . $cliente->id,
            'telefono' => 'required|string|max:20',
            'direccion' => 'required|string|max:255',
            'correo' => 'required|email|max:255|unique:clientes,correo,' . $cliente->id,
        ]);

        $cliente->update($request->all());

        return redirect()->route('clientes.index')
                         ->with('success', 'Cliente actualizado exitosamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Cliente $cliente)
    {
        $cliente->delete();

        return redirect()->route('clientes.index')
                         ->with('success', 'Cliente eliminado exitosamente.');
    }
}
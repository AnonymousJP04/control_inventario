<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class ClienteController extends Controller
{
    /**
     * Display a listing of the clients.
     */
    public function index()
    {
        $clientes = Cliente::with('user')->latest()->paginate(10);
        return view('clientes.index', compact('clientes')); // Asegúrate de tener esta vista
    }

    /**
     * Show the form for creating a new client.
     */
    public function create()
    {
        return view('clientes.create'); // Asegúrate de tener esta vista
    }

    /**
     * Store a newly created client in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'nit' => 'nullable|string|max:20|unique:clientes,nit',
            'telefono' => 'nullable|string|max:20|unique:clientes,telefono',
            'direccion' => 'nullable|string|max:255',
            'correo' => 'nullable|string|email|max:255|unique:clientes,correo',
        ]);

        Cliente::create($request->all() + ['user_id' => Auth::id()]);

        return redirect()->route('clientes.index')
                         ->with('success', 'Cliente creado exitosamente.');
    }

    /**
     * Display the specified client.
     */
    public function show(Cliente $cliente)
    {
        $cliente->load(['user', 'ventas']); // Cargar ventas del cliente
        return view('clientes.show', compact('cliente')); // Asegúrate de tener esta vista
    }

    /**
     * Show the form for editing the specified client.
     */
    public function edit(Cliente $cliente)
    {
        return view('clientes.edit', compact('cliente')); // Asegúrate de tener esta vista
    }

    /**
     * Update the specified client in storage.
     */
    public function update(Request $request, Cliente $cliente)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'nit' => ['nullable','string','max:20', Rule::unique('clientes')->ignore($cliente->id)],
            'telefono' => ['nullable','string','max:20', Rule::unique('clientes')->ignore($cliente->id)],
            'direccion' => 'nullable|string|max:255',
            'correo' => ['nullable','string','email','max:255', Rule::unique('clientes')->ignore($cliente->id)],
        ]);

        $cliente->update($request->all());

        return redirect()->route('clientes.index')
                         ->with('success', 'Cliente actualizado exitosamente.');
    }

    /**
     * Remove the specified client from storage.
     */
    public function destroy(Cliente $cliente)
    {
        // Considerar si se pueden eliminar clientes con ventas (depende de la FK en ventas y reglas de negocio)
        try {
            $cliente->delete();
            return redirect()->route('clientes.index')
                             ->with('success', 'Cliente eliminado exitosamente.');
        } catch (\Illuminate\Database\QueryException $e) {
            return redirect()->route('clientes.index')
                             ->with('error', 'No se pudo eliminar el cliente. Puede estar asociado a ventas.');
        }
    }
}
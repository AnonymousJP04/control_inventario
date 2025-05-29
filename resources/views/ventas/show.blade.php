{{-- filepath: resources/views/ventas/show.blade.php --}}
@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto py-6 sm:px-6 lg:px-8">
    <h2 class="text-2xl font-semibold text-gray-700 dark:text-gray-200 mb-4">Detalle de Venta #{{ $venta->id }}</h2>

    <div class="bg-white dark:bg-gray-800 shadow rounded p-6 mb-4">
        <p><strong>Cliente:</strong> {{ $venta->cliente->nombre ?? '-' }}</p>
        <p><strong>Usuario:</strong> {{ $venta->user->name ?? '-' }}</p>
        <p><strong>Total:</strong> ${{ number_format($venta->total, 2) }}</p>
        <p><strong>Fecha:</strong> {{ $venta->created_at->format('d/m/Y H:i') }}</p>
    </div>

    <h3 class="text-lg font-semibold text-gray-600 dark:text-gray-300 mb-2">Productos vendidos</h3>
    <div class="bg-white dark:bg-gray-800 shadow rounded p-4">
        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
            <thead>
                <tr>
                    <th class="px-4 py-2 text-left">Producto</th>
                    <th class="px-4 py-2 text-left">Cantidad</th>
                    <th class="px-4 py-2 text-left">Precio</th>
                    <th class="px-4 py-2 text-left">Subtotal</th>
                </tr>
            </thead>
            <tbody>
                @foreach($venta->detalles as $detalle)
                    <tr>
                        <td class="px-4 py-2">{{ $detalle->producto->nombre ?? '-' }}</td>
                        <td class="px-4 py-2">{{ $detalle->cantidad }}</td>
                        <td class="px-4 py-2">${{ number_format($detalle->precio, 2) }}</td>
                        <td class="px-4 py-2">${{ number_format($detalle->cantidad * $detalle->precio, 2) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        <a href="{{ route('ventas.index') }}" class="text-blue-600 hover:underline">Volver a ventas</a>
    </div>
</div>
@endsection
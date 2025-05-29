{{-- filepath: resources/views/ventas/show.blade.php --}}
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Detalles de la Venta') }} #{{ $venta->id }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
                
                <div class="flex justify-between items-center mb-6">
                    <h1 class="text-xl font-semibold text-gray-900 dark:text-gray-100">Información General</h1>
                    <div>
                        <a href="{{ route('ventas.edit', $venta) }}" class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded mr-2 transition">
                            Editar
                        </a>
                        <a href="{{ route('ventas.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded transition">
                            Volver al Listado
                        </a>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-4 text-sm text-gray-700 dark:text-gray-300">
                    <div>
                        <strong class="font-medium text-gray-900 dark:text-gray-100">Cliente:</strong>
                        <p>{{ $venta->cliente->nombre }}</p>
                    </div>
                    <div>
                        <strong class="font-medium text-gray-900 dark:text-gray-100">Vendedor:</strong>
                        <p>{{ $venta->user->name }}</p>
                    </div>
                    <div>
                        <strong class="font-medium text-gray-900 dark:text-gray-100">Fecha de Venta:</strong>
                        <p>{{ $venta->created_at->format('d/m/Y H:i A') }}</p>
                    </div>
                    <div>
                        <strong class="font-medium text-gray-900 dark:text-gray-100">Total:</strong>
                        <p>Q. {{ number_format($venta->total, 2) }}</p>
                    </div>
                </div>

                <div class="mt-8">
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">Productos Vendidos</h2>
                    @if ($venta->detalleVentas->count() > 0)
                        <div class="overflow-x-auto border border-gray-300 rounded-md dark:border-gray-600">
                            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                <thead class="bg-gray-50 dark:bg-gray-700">
                                    <tr>
                                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Producto</th>
                                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Cantidad</th>
                                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Precio Unitario</th>
                                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Descuento</th>
                                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Subtotal</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                    @foreach ($venta->detalleVentas as $detalle)
                                        <tr>
                                            <td class="px-4 py-2">{{ $detalle->producto->nombre }}</td>
                                            <td class="px-4 py-2">{{ $detalle->cantidad }}</td>
                                            <td class="px-4 py-2">Q. {{ number_format($detalle->precio_unitario, 2) }}</td>
                                            <td class="px-4 py-2">Q. {{ number_format($detalle->descuento, 2) }}</td>
                                            <td class="px-4 py-2">
                                                Q. {{ number_format(($detalle->precio_unitario * $detalle->cantidad) - $detalle->descuento, 2) }}
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p class="text-sm text-gray-600 dark:text-gray-400">No hay productos asociados a esta venta.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

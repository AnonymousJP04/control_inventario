<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Listado de Ventas') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">

                <div class="flex justify-between items-center mb-6">
                    <h1 class="text-xl font-semibold text-gray-900 dark:text-gray-100">Ventas</h1>
                    <a href="{{ route('ventas.create') }}" 
                       class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded transition">
                        + Nueva Venta
                    </a>
                </div>

                @if (session('success'))
                    <div class="bg-green-100 border border-green-400 text-green-700 dark:bg-green-700 dark:text-green-100 dark:border-green-600 px-4 py-3 rounded relative mb-4" role="alert">
                        <span class="block sm:inline">{{ session('success') }}</span>
                    </div>
                @endif

                <div class="overflow-x-auto">
                    <table class="min-w-full leading-normal table-auto w-full border dark:border-gray-700">
                        <thead class="bg-gray-100 dark:bg-gray-700">
                            <tr>
                                <th class="px-5 py-3 border-b-2 border-gray-200 dark:border-gray-600 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">
                                    #
                                </th>
                                <th class="px-5 py-3 border-b-2 border-gray-200 dark:border-gray-600 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">
                                    Cliente
                                </th>
                                <th class="px-5 py-3 border-b-2 border-gray-200 dark:border-gray-600 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">
                                    Vendedor
                                </th>
                                <th class="px-5 py-3 border-b-2 border-gray-200 dark:border-gray-600 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">
                                    Total
                                </th>
                                <th class="px-5 py-3 border-b-2 border-gray-200 dark:border-gray-600 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">
                                    Fecha
                                </th>
                                <th class="px-5 py-3 border-b-2 border-gray-200 dark:border-gray-600 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">
                                    Productos
                                </th>
                                <th class="px-5 py-3 border-b-2 border-gray-200 dark:border-gray-600 text-right text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">
                                    Acciones
                                </th>
                            </tr>
                        </thead>
                        <tbody class="text-gray-700 dark:text-gray-300">
                            @forelse($ventas as $venta)
                                <tr>
                                    <td class="px-5 py-5 border-b border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 text-sm">
                                        {{ $venta->id }}
                                    </td>
                                    <td class="px-5 py-5 border-b border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 text-sm">
                                        {{ $venta->cliente->nombre }}
                                    </td>
                                    <td class="px-5 py-5 border-b border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 text-sm">
                                        {{ $venta->user->name }}
                                    </td>
                                    <td class="px-5 py-5 border-b border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 text-sm">
                                        Q. {{ number_format($venta->total, 2) }}
                                    </td>
                                    <td class="px-5 py-5 border-b border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 text-sm">
                                        {{ $venta->created_at->format('d/m/Y') }}
                                    </td>
                                    <td class="px-5 py-5 border-b border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 text-sm">
                                        <ul class="list-disc list-inside">
                                            @foreach($venta->detalleVentas as $detalle)
                                                <li>{{ $detalle->producto->nombre }} (x{{ $detalle->cantidad }})</li>
                                            @endforeach
                                        </ul>
                                    </td>
                                    <td class="px-5 py-5 border-b border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 text-sm text-right space-x-2">
                                        <a href="{{ route('ventas.show', $venta) }}" class="text-blue-500 hover:underline">Ver</a>
                                        <a href="{{ route('ventas.edit', $venta) }}" class="text-indigo-500 hover:underline">Editar</a>
                                    <form action="{{ route('ventas.destroy', $venta) }}" method="POST" class="inline-block" onsubmit="return confirm('¿Está seguro de eliminar esta venta?');">
                                         @csrf
                                         @method('DELETE')
                                     <button type="submit" class="text-red-500 hover:underline">Eliminar</button>
                                    </form>

                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="px-5 py-5 border-b border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 text-sm text-center">
                                        No hay ventas registradas.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="mt-6">
                    {{ $ventas->links() }}
                </div>

            </div>
        </div>
    </div>
</x-app-layout>

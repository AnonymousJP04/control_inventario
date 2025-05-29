{{-- filepath: resources/views/dashboard.blade.php --}}
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            {{-- Tarjetas de Resumen --}}
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-6">
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">Total Productos</h3>
                    <p class="mt-1 text-3xl font-semibold text-gray-700 dark:text-gray-300">{{ $totalProductos }}</p>
                </div>
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">Total Clientes</h3>
                    <p class="mt-1 text-3xl font-semibold text-gray-700 dark:text-gray-300">{{ $totalClientes }}</p>
                </div>
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">Total Usuarios</h3>
                    <p class="mt-1 text-3xl font-semibold text-gray-700 dark:text-gray-300">{{ $totalUsuarios }}</p>
                </div>
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">Ventas Hoy</h3>
                    <p class="mt-1 text-3xl font-semibold text-gray-700 dark:text-gray-300">{{ $ventasHoy }}</p>
                </div>
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">Monto Vendido Hoy</h3>
                    <p class="mt-1 text-3xl font-semibold text-gray-700 dark:text-gray-300">Q {{ number_format($montoVendidoHoy, 2) }}</p>
                </div>
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">Total Descuentos Otorgados</h3>
                    <p class="mt-1 text-3xl font-semibold text-gray-700 dark:text-gray-300">Q {{ number_format($totalDescuentos, 2) }}</p>
                </div>
                 <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">Productos Bajo Stock</h3>
                    <p class="mt-1 text-3xl font-semibold text-red-500">{{ $productosBajoStock }}</p>
                </div>
            </div>

            {{-- Listas y Gráficos (ejemplos) --}}
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">Top 5 Clientes (por monto)</h3>
                    <ul class="space-y-2">
                        @forelse ($topClientes as $cliente)
                            <li class="text-gray-700 dark:text-gray-300">{{ $cliente->nombre }} - Q {{ number_format($cliente->monto_total_compras, 2) }}</li>
                        @empty
                            <li class="text-gray-500 dark:text-gray-400">No hay datos de clientes.</li>
                        @endforelse
                    </ul>
                </div>

                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">Top 5 Productos Más Vendidos</h3>
                    <ul class="space-y-2">
                        @forelse ($productosMasVendidos as $producto)
                            <li class="text-gray-700 dark:text-gray-300">{{ $producto->nombre_producto }} ({{ $producto->total_vendido }} unidades)</li>
                        @empty
                            <li class="text-gray-500 dark:text-gray-400">No hay datos de productos vendidos.</li>
                        @endforelse
                    </ul>
                </div>
            </div>

            {{-- Puedes añadir más secciones aquí --}}

        </div>
    </div>
</x-app-layout>
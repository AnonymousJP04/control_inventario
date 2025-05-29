{{-- filepath: resources/views/clientes/show.blade.php --}}
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Detalles del Cliente') }}: {{ $cliente->nombre }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
                <div class="flex justify-between items-center mb-6">
                    <h1 class="text-xl font-semibold text-gray-900 dark:text-gray-100">Información del Cliente</h1>
                    <div>
                        <a href="{{ route('clientes.edit', $cliente) }}" class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded mr-2 transition">
                            Editar
                        </a>
                        <a href="{{ route('clientes.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded transition">
                            Volver al Listado
                        </a>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-4 text-sm text-gray-700 dark:text-gray-300">
                    <div>
                        <strong class="font-medium text-gray-900 dark:text-gray-100">Nombre Completo:</strong>
                        <p>{{ $cliente->nombre }}</p>
                    </div>
                    <div>
                        <strong class="font-medium text-gray-900 dark:text-gray-100">NIT:</strong>
                        <p>{{ $cliente->nit ?? 'N/A' }}</p>
                    </div>
                    <div>
                        <strong class="font-medium text-gray-900 dark:text-gray-100">Teléfono:</strong>
                        <p>{{ $cliente->telefono ?? 'N/A' }}</p>
                    </div>
                    <div>
                        <strong class="font-medium text-gray-900 dark:text-gray-100">Dirección:</strong>
                        <p>{{ $cliente->direccion ?? 'N/A' }}</p>
                    </div>
                    <div>
                        <strong class="font-medium text-gray-900 dark:text-gray-100">Correo Electrónico:</strong>
                        <p>{{ $cliente->correo ?? 'N/A' }}</p>
                    </div>
                    <div>
                        <strong class="font-medium text-gray-900 dark:text-gray-100">Registrado por:</strong>
                        <p>{{ $cliente->user->name ?? 'Desconocido' }}</p>
                    </div>
                    <div>
                        <strong class="font-medium text-gray-900 dark:text-gray-100">Fecha de Registro:</strong>
                        <p>{{ $cliente->created_at->format('d/m/Y H:i A') }}</p>
                    </div>
                    <div>
                        <strong class="font-medium text-gray-900 dark:text-gray-100">Última Actualización:</strong>
                        <p>{{ $cliente->updated_at->format('d/m/Y H:i A') }}</p>
                    </div>
                </div>

                {{-- Sección para mostrar ventas del cliente (opcional) --}}
                <div class="mt-8">
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">Ventas Realizadas</h2>
                    @if($cliente->ventas && $cliente->ventas->count() > 0)
                        <ul class="list-disc pl-5 space-y-1 text-sm text-gray-700 dark:text-gray-300">
                            @foreach($cliente->ventas as $venta)
                                <li>
                                    <a href="{{ route('ventas.show', $venta) }}" class="text-blue-600 dark:text-blue-400 hover:underline">
                                        Venta #{{ $venta->id }} - Total: {{ number_format($venta->total, 2) }} ({{ $venta->created_at->format('d/m/Y') }})
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    @else
                        <p class="text-sm text-gray-600 dark:text-gray-400">Este cliente aún no tiene ventas registradas.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
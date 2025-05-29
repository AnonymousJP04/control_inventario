<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Editar Venta') }}
        </h2>
    </x-slot>

    <div class="py-12 max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">

            <!-- Botón regresar -->
            <div class="mb-6">
                <a href="{{ route('ventas.index') }}" 
                   class="inline-block bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded transition">
                    ← Regresar al listado de ventas
                </a>
            </div>

            @if ($errors->any())
                <div class="mb-4 p-4 bg-red-100 text-red-700 rounded">
                    <ul class="list-disc list-inside">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('ventas.update', $venta) }}" method="POST" id="formVenta" class="space-y-6">
                @csrf
                @method('PUT')

                <!-- Seleccionar cliente -->
                <div>
                    <label for="cliente_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Cliente</label>
                    <select name="cliente_id" id="cliente_id" 
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm
                               focus:border-blue-500 focus:ring focus:ring-blue-200
                               dark:bg-gray-700 dark:border-gray-600 dark:text-gray-200
                               dark:focus:ring-blue-500">
                        @foreach ($clientes as $cliente)
                            <option value="{{ $cliente->id }}" 
                                {{ $venta->cliente_id == $cliente->id ? 'selected' : '' }}>
                                {{ $cliente->nombre }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Detalles de la Venta</h3>

                <div class="overflow-x-auto border border-gray-300 rounded-md dark:border-gray-600">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-gray-50 dark:bg-gray-700">
                            <tr>
                                <th scope="col" class="px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Producto</th>
                                <th scope="col" class="px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Cantidad</th>
                                <th scope="col" class="px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Precio Unitario</th>
                                <th scope="col" class="px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Descuento</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200 dark:bg-gray-800 dark:divide-gray-700">
                            @foreach ($venta->detalleVentas as $index => $detalle)
                            <tr>
                                <td class="px-4 py-2 whitespace-nowrap">
                                    <select name="detalles_venta[{{ $index }}][producto_id]" 
                                            class="block w-full rounded-md border-gray-300 shadow-sm
                                                   focus:border-blue-500 focus:ring focus:ring-blue-200
                                                   dark:bg-gray-700 dark:border-gray-600 dark:text-gray-200
                                                   dark:focus:ring-blue-500">
                                        @foreach ($productos as $producto)
                                            <option value="{{ $producto->id }}" 
                                                {{ $detalle->producto_id == $producto->id ? 'selected' : '' }}>
                                                {{ $producto->nombre }}
                                            </option>
                                        @endforeach
                                    </select>
                                </td>
                                <td class="px-4 py-2 whitespace-nowrap">
                                    <input type="number" name="detalles_venta[{{ $index }}][cantidad]" 
                                        value="{{ $detalle->cantidad }}" min="1"
                                        class="block w-full rounded-md border-gray-300 shadow-sm
                                               focus:border-blue-500 focus:ring focus:ring-blue-200
                                               dark:bg-gray-700 dark:border-gray-600 dark:text-gray-200
                                               dark:focus:ring-blue-500">
                                </td>
                                <td class="px-4 py-2 whitespace-nowrap">
                                    <input type="number" name="detalles_venta[{{ $index }}][precio_unitario]" 
                                        value="{{ $detalle->precio_unitario }}" step="0.01" min="0"
                                        class="block w-full rounded-md border-gray-300 shadow-sm
                                               focus:border-blue-500 focus:ring focus:ring-blue-200
                                               dark:bg-gray-700 dark:border-gray-600 dark:text-gray-200
                                               dark:focus:ring-blue-500">
                                </td>
                                <td class="px-4 py-2 whitespace-nowrap">
                                    <input type="number" name="detalles_venta[{{ $index }}][descuento]" 
                                        value="{{ $detalle->descuento }}" step="0.01" min="0"
                                        class="block w-full rounded-md border-gray-300 shadow-sm
                                               focus:border-blue-500 focus:ring focus:ring-blue-200
                                               dark:bg-gray-700 dark:border-gray-600 dark:text-gray-200
                                               dark:focus:ring-blue-500">
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div>
                    <button type="submit" 
                            class="inline-flex justify-center py-2 px-6 border border-transparent shadow-sm text-sm font-medium rounded-md
                                   text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        Actualizar Venta
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>

<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Movimiento de Productos') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">

                <a href="{{ route('productos.index') }}" class="bg-blue-500 text-white px-4 py-2 rounded">Agregar Producto</a>

                <!-- Formulario Entrada -->
                <h2 class="text-xl font-bold mt-6">Registrar Entrada</h2>
                <form method="POST" action="{{ route('stock.entrada') }}" class="grid grid-cols-3 gap-4 mt-2">
                    @csrf
                    <select name="producto_id" class="border p-2">
                        @foreach($productos as $producto)
                            <option value="{{ $producto->id }}">{{ $producto->nombre }}</option>
                        @endforeach
                    </select>
                    <input type="number" name="cantidad" placeholder="Cantidad" class="border p-2">
                    <button type="submit" class="bg-blue-500 text-white px-4 py-2">Registrar Entrada</button>
                </form>

                <!-- Formulario Salida -->
                <h2 class="text-xl font-bold mt-6">Registrar Salida</h2>
                <form method="POST" action="{{ route('stock.salida') }}" class="grid grid-cols-3 gap-4 mt-2">
                    @csrf
                    <select name="producto_id" class="border p-2">
                        @foreach($productos as $producto)
                            <option value="{{ $producto->id }}">{{ $producto->nombre }}</option>
                        @endforeach
                    </select>
                    <input type="number" name="cantidad" placeholder="Cantidad" class="border p-2">
                    <button type="submit" class="bg-red-500 text-white px-4 py-2">Registrar Salida</button>
                </form>

               <table class="table-auto w-full mt-4">
                    <thead>
                        <tr>
                            <th class="px-4 py-2">Nombre</th>
                            <th class="px-4 py-2">Precio</th>
                            <th class="px-4 py-2">Stock Minimo</th>
                            <th class="px-4 py-2">Stock Actual</th>
                            <th class="px-4 py-2">Usuario</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($productos as $producto)
                            <tr>
                                <td class="border px-4 py-2">{{ $producto->nombre }}</td>
                                <td class="border px-4 py-2">Q{{ number_format($producto->precio, 2) }}</td>
                                <td class="border px-4 py-2">{{ $producto->stock_minimo }}</td>
                                <td class="border px-4 py-2">{{ $producto->stockActual() }}</td>
                                <td class="border px-4 py-2">{{ $producto->user ? $producto->user->name : 'N/A' }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>
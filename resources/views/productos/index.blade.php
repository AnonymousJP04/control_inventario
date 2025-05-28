<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Catalogo de Productos') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
  

        
        <div class="flex justify-between items-center mb-4">
            <a href="{{ route('productos.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 transition">
                + Agregar Producto
            </a>

            <a href="{{ route('stock.index') }}" class="bg-gray-600 text-white px-4 py-2 rounded hover:bg-gray-700 transition">
                Movimientos de Stock
            </a>
        </div>


        @if (session('success'))
            <div class="bg-green-100 text-green-800 p-3 rounded mb-4">
                {{ session('success') }}
            </div>
        @endif

        <table class="table-auto w-full border">
            <thead class="bg-gray-100">
                <tr>
                    <th class="px-4 py-2 border">ID</th>
                    <th class="px-4 py-2 border">Nombre</th>
                    <th class="px-4 py-2 border">Descripcion</th>
                    <th class="px-4 py-2 border">Precio</th>
                    <th class="px-4 py-2 border">Stock mínimo</th>
                    <th class="px-4 py-2 border">Usuario Registro</th>
                    <th class="px-4 py-2 border">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($productos as $producto)
                    <tr>
                        <td class="px-4 py-2 border text-center">{{ $producto->id }}</td>
                        <td class="px-4 py-2 border">{{ $producto->nombre }}</td>
                        <td class="px-4 py-2 border">{{ $producto->descripcion }}</td>
                        <td class="px-4 py-2 border text-center">{{ $producto->precio }}</td>
                        <td class="px-4 py-2 border text-center">{{ $producto->stock_minimo }}</td>
                        <td class="px-4 py-2 border text-center">{{ $producto->user ? $producto->user->name : 'N/A' }}</td>
                        <td class="px-4 py-2 border space-x-2 text-center">
                            <a href="{{ route('productos.edit', $producto) }}" class="text-blue-500 hover:underline">Editar</a>
                            <form action="{{ route('productos.destroy', $producto) }}" method="POST" class="inline-block" onsubmit="return confirm('¿Estás seguro?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-500 hover:underline">Eliminar</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
        </div>
    </div>
</x-app-layout>
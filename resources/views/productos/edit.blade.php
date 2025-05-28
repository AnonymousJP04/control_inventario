<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Editar Producto') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
         

        <form method="POST" action="{{ route('productos.update', $producto) }}" class="space-y-4">
            @csrf
            @method('PUT')

            <div>
                <label class="block">Nombre</label>
                <input type="text" name="nombre" class="w-full border p-2 rounded" value="{{ $producto->nombre }}" required>
            </div>

            <div>
                <label class="block">Descripción</label>
                <textarea name="descripcion" class="w-full border p-2 rounded">{{ $producto->descripcion }}</textarea>
            </div>

            <div>
                <label class="block">Precio</label>
                <input type="number" name="precio" class="w-full border p-2 rounded" min="0" step="0.01" value="{{ $producto->precio }}" required>
            </div>

            <div>
                <label class="block">Stock mínimo</label>
                <input type="number" name="stock_minimo" class="w-full border p-2 rounded" min="0" value="{{ $producto->stock_minimo }}" required>
            </div>

            <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded">Actualizar</button>
            <a href="{{ route('productos.index') }}" class="bg-red-500 text-white px-4 py-2 rounded">Cancelar</a>
        </form>
   </div>
        </div>
    </div>
</x-app-layout>
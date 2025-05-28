<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Nuevo Producto') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
        <form method="POST" action="{{ route('productos.store') }}" class="space-y-4">
            @csrf
            <div>
                <label class="block">Nombre</label>
                <input type="text" name="nombre" class="w-full border p-2 rounded" required>
            </div>

            <div>
                <label class="block">Descripción</label>
                <input type="text" name="descripcion" class="w-full border p-2 rounded" required>
            </div>

            <div>
                <label class="block">Precio</label>
                <input type="number" name="precio" class="w-full border p-2 rounded" min="0" step="0.01" required>
            </div>

            <div>
                <label class="block">Stock mínimo</label>
                <input type="number" name="stock_minimo" class="w-full border p-2 rounded" min="0" required>
            </div>

            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">Guardar</button>
            <a href="{{ route('productos.index') }}" class="bg-red-500 text-white px-4 py-2 rounded">Cancelar</a>
        </form>
   </div>
        </div>
    </div>
</x-app-layout>
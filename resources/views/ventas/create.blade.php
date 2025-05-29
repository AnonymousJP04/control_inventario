<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Registrar Venta') }}
        </h2>
    </x-slot>

    <div class="py-12 max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
            
            @if ($errors->any())
                <div class="mb-4 p-4 bg-red-100 text-red-700 rounded">
                    <ul class="list-disc list-inside">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('ventas.store') }}" method="POST" id="formVenta">
                @csrf

                <div class="mb-6">
                    <label for="cliente_id" class="block font-medium text-gray-700 dark:text-gray-300 mb-1">Cliente:</label>
                    <select name="cliente_id" id="cliente_id" required class="w-full rounded border-gray-300 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-200">
                        <option value="" disabled selected>Selecciona un cliente</option>
                        @foreach ($clientes as $cliente)
                            <option value="{{ $cliente->id }}" {{ old('cliente_id') == $cliente->id ? 'selected' : '' }}>
                                {{ $cliente->nombre }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-6 overflow-x-auto">
                    <table class="w-full border border-gray-300 dark:border-gray-600 text-sm">
                        <thead class="bg-gray-100 dark:bg-gray-700">
                            <tr>
                                <th class="border px-2 py-1 text-left">Producto</th>
                                <th class="border px-2 py-1 w-24 text-center">Cantidad</th>
                                <th class="border px-2 py-1 w-28 text-center">Precio Unitario (Q)</th>
                                <th class="border px-2 py-1 w-28 text-center">Descuento (Q)</th>
                                <th class="border px-2 py-1 w-24 text-right">Subtotal (Q)</th>
                                <th class="border px-2 py-1 w-16 text-center">Acción</th>
                            </tr>
                        </thead>
                        <tbody id="detallesVentaBody">
                            @if(old('detalles_venta'))
                                @foreach(old('detalles_venta') as $index => $detalle)
                                    <tr>
                                        <td class="border px-2 py-1">
                                            <select name="detalles_venta[{{ $index }}][producto_id]" required class="w-full rounded border-gray-300 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-200">
                                                <option value="" disabled>Seleccione</option>
                                                @foreach ($productos as $producto)
                                                    <option value="{{ $producto->id }}" 
                                                        {{ $detalle['producto_id'] == $producto->id ? 'selected' : '' }}>
                                                        {{ $producto->nombre }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </td>
                                        <td class="border px-2 py-1 text-center">
                                            <input type="number" min="1" name="detalles_venta[{{ $index }}][cantidad]" 
                                                class="w-full rounded border-gray-300 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-200 text-center"
                                                value="{{ $detalle['cantidad'] }}" required>
                                        </td>
                                        <td class="border px-2 py-1 text-center">
                                            <input type="number" step="0.01" min="0" name="detalles_venta[{{ $index }}][precio_unitario]" 
                                                class="w-full rounded border-gray-300 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-200 text-center"
                                                value="{{ $detalle['precio_unitario'] ?? '' }}" placeholder="Opcional" readonly>
                                        </td>
                                        <td class="border px-2 py-1 text-center">
                                            <input type="number" step="0.01" min="0" name="detalles_venta[{{ $index }}][descuento]" 
                                                class="w-full rounded border-gray-300 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-200 text-center"
                                                value="{{ $detalle['descuento'] ?? 0 }}" placeholder="0.00">
                                        </td>
                                        <td class="border px-2 py-1 text-right subtotal-cell">Q0.00</td>
                                        <td class="border px-2 py-1 text-center">
                                            <button type="button" class="eliminar-fila text-red-600 hover:text-red-900 font-bold">X</button>
                                        </td>
                                    </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td class="border px-2 py-1">
                                        <select name="detalles_venta[0][producto_id]" required class="w-full rounded border-gray-300 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-200">
                                            <option value="" disabled selected>Seleccione</option>
                                            @foreach ($productos as $producto)
                                                <option value="{{ $producto->id }}">{{ $producto->nombre }}</option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td class="border px-2 py-1 text-center">
                                        <input type="number" min="1" name="detalles_venta[0][cantidad]" value="1" required
                                            class="w-full rounded border-gray-300 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-200 text-center">
                                    </td>
                                    <td class="border px-2 py-1 text-center">
                                        <input type="number" step="0.01" min="0" name="detalles_venta[0][precio_unitario]" placeholder="Opcional"
                                            class="w-full rounded border-gray-300 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-200 text-center" readonly>
                                    </td>
                                    <td class="border px-2 py-1 text-center">
                                        <input type="number" step="0.01" min="0" name="detalles_venta[0][descuento]" value="0" placeholder="0.00"
                                            class="w-full rounded border-gray-300 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-200 text-center">
                                    </td>
                                    <td class="border px-2 py-1 text-right subtotal-cell">Q0.00</td>
                                    <td class="border px-2 py-1 text-center">
                                        <button type="button" class="eliminar-fila text-red-600 hover:text-red-900 font-bold">X</button>
                                    </td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>

                <div class="mb-6 flex justify-between items-center">
                    <button type="button" id="btnAgregarProducto" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700 transition">
                        + Agregar Producto
                    </button>

                    <div class="text-lg font-semibold text-gray-900 dark:text-gray-200">
                        Total: <span id="totalVenta">Q0.00</span>
                    </div>
                </div>

                <div class="flex flex-col items-start gap-4 mb-6">
                    <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded hover:bg-blue-700 transition">
                        Guardar Venta
                    </button>

                    <a href="{{ route('ventas.index') }}" 
                       class="inline-block bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded transition">
                        ← Regresar al listado de ventas
                    </a>
                </div>

            </form>
        </div>
    </div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const detallesBody = document.getElementById('detallesVentaBody');
        const btnAgregar = document.getElementById('btnAgregarProducto');
        const totalVentaSpan = document.getElementById('totalVenta');

        // Lista de productos con precios (desde Blade a JS)
        const productos = @json($productos);

        // Función para obtener precio por producto id
        function obtenerPrecioProducto(id) {
            const prod = productos.find(p => p.id === Number(id));
            return prod ? Number(prod.precio) : 0;
        }

        // Actualiza el precio unitario cuando cambia el producto
        function actualizarPrecioUnitario(fila) {
            const selectProducto = fila.querySelector('select[name$="[producto_id]"]');
            const inputPrecio = fila.querySelector('input[name$="[precio_unitario]"]');
            if (selectProducto && inputPrecio) {
                const precio = obtenerPrecioProducto(selectProducto.value);
                if (precio > 0) {
                    inputPrecio.value = precio.toFixed(2);
                } else {
                    inputPrecio.value = '';
                }
            }
        }

        // Calcula subtotal de una fila
        function calcularSubtotal(fila) {
            const cantidad = parseFloat(fila.querySelector('input[name$="[cantidad]"]').value) || 0;
            let precio = parseFloat(fila.querySelector('input[name$="[precio_unitario]"]').value);
            if (isNaN(precio) || precio <= 0) {
                const productoId = fila.querySelector('select[name$="[producto_id]"]').value;
                precio = obtenerPrecioProducto(productoId);
            }
            const descuento = parseFloat(fila.querySelector('input[name$="[descuento]"]').value) || 0;
            let subtotal = (cantidad * precio) - descuento;
            return subtotal < 0 ? 0 : subtotal.toFixed(2);
        }

        // Actualiza todos los subtotales y el total
        function actualizarTotales() {
            let total = 0;
            detallesBody.querySelectorAll('tr').forEach(fila => {
                const subtotalCelda = fila.querySelector('.subtotal-cell');
                const subtotal = calcularSubtotal(fila);
                subtotalCelda.textContent = 'Q' + subtotal;
                total += parseFloat(subtotal);
            });
            totalVentaSpan.textContent = 'Q' + total.toFixed(2);
        }

        // Inicializa los precios unitarios en las filas existentes (al cargar)
        function inicializarPrecios() {
            detallesBody.querySelectorAll('tr').forEach(fila => {
                actualizarPrecioUnitario(fila);
            });
        }

        // Evento: cuando cambia el select de producto, actualiza el precio y totales
        detallesBody.addEventListener('change', function (e) {
            if (e.target.matches('select[name$="[producto_id]"]')) {
                const fila = e.target.closest('tr');
                actualizarPrecioUnitario(fila);
                actualizarTotales();
            }
        });

        // Evento: cuando cambia cantidad o descuento, actualizar totales
        detallesBody.addEventListener('input', function (e) {
            if (
                e.target.matches('input[name$="[cantidad]"]') ||
                e.target.matches('input[name$="[descuento]"]')
            ) {
                const fila = e.target.closest('tr');
                actualizarTotales();
            }
        });

        // Botón agregar producto: añade fila nueva con selects y inputs
        btnAgregar.addEventListener('click', function () {
            const numFilas = detallesBody.querySelectorAll('tr').length;
            const nuevaFila = document.createElement('tr');

            let opcionesProductos = '<option value="" disabled selected>Seleccione</option>';
            productos.forEach(prod => {
                opcionesProductos += `<option value="${prod.id}">${prod.nombre}</option>`;
            });

            nuevaFila.innerHTML = `
                <td class="border px-2 py-1">
                    <select name="detalles_venta[${numFilas}][producto_id]" required class="w-full rounded border-gray-300 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-200">
                        ${opcionesProductos}
                    </select>
                </td>
                <td class="border px-2 py-1 text-center">
                    <input type="number" min="1" name="detalles_venta[${numFilas}][cantidad]" value="1" required
                        class="w-full rounded border-gray-300 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-200 text-center">
                </td>
                <td class="border px-2 py-1 text-center">
                    <input type="number" step="0.01" min="0" name="detalles_venta[${numFilas}][precio_unitario]" placeholder="Opcional" readonly
                        class="w-full rounded border-gray-300 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-200 text-center">
                </td>
                <td class="border px-2 py-1 text-center">
                    <input type="number" step="0.01" min="0" name="detalles_venta[${numFilas}][descuento]" value="0" placeholder="0.00"
                        class="w-full rounded border-gray-300 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-200 text-center">
                </td>
                <td class="border px-2 py-1 text-right subtotal-cell">Q0.00</td>
                <td class="border px-2 py-1 text-center">
                    <button type="button" class="eliminar-fila text-red-600 hover:text-red-900 font-bold">X</button>
                </td>
            `;

            detallesBody.appendChild(nuevaFila);
            actualizarTotales();
        });

        // Evento para eliminar fila
        detallesBody.addEventListener('click', function (e) {
            if (e.target.classList.contains('eliminar-fila')) {
                const filas = detallesBody.querySelectorAll('tr');
                if (filas.length > 1) {
                    e.target.closest('tr').remove();
                    actualizarTotales();
                } else {
                    alert('Debe haber al menos un producto en la venta.');
                }
            }
        });

        // Inicializa todo al cargar la página
        inicializarPrecios();
        actualizarTotales();

    });
</script>
</x-app-layout>

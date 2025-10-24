@extends('layouts.app')

@section('title', 'Nueva Venta - Casa Maravillosa')
@section('page-title', 'Nueva Venta')
@section('page-subtitle', 'Registra una nueva venta en el sistema')

@section('content')
<div class="max-w-7xl mx-auto">

    @if(session('error'))
    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-6">
        {{ session('error') }}
    </div>
    @endif

    <form action="{{ route('ventas.store') }}" method="POST" id="formVenta">
        @csrf

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            
            <!-- Columna izquierda - Información del cliente -->
            <div class="lg:col-span-1 space-y-6">
                
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h3 class="text-lg font-bold text-gray-800 mb-4">Información del Cliente</h3>
                    
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Cliente *</label>
                        <select name="cliente_id" required id="selectCliente"
                                class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 @error('cliente_id') border-red-500 @enderror">
                            <option value="">Seleccionar cliente...</option>
                            @foreach($clientes as $cliente)
                            <option value="{{ $cliente->id }}" 
                                    data-telefono="{{ $cliente->telefono }}"
                                    data-email="{{ $cliente->email }}"
                                    data-vehiculo="{{ ucfirst($cliente->tipo_vehiculo) }}">
                                {{ $cliente->nombre_completo }}
                            </option>
                            @endforeach
                        </select>
                        @error('cliente_id')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div id="infoCliente" class="hidden space-y-2 text-sm border-t pt-4">
                        <div class="flex items-center gap-2 text-gray-600">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M2 3a1 1 0 011-1h2.153a1 1 0 01.986.836l.74 4.435a1 1 0 01-.54 1.06l-1.548.773a11.037 11.037 0 006.105 6.105l.774-1.548a1 1 0 011.059-.54l4.435.74a1 1 0 01.836.986V17a1 1 0 01-1 1h-2C7.82 18 2 12.18 2 5V3z"/>
                            </svg>
                            <span id="clienteTelefono"></span>
                        </div>
                        <div class="flex items-center gap-2 text-gray-600">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z"/>
                                <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z"/>
                            </svg>
                            <span id="clienteEmail"></span>
                        </div>
                        <div class="flex items-center gap-2 text-gray-600">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M8 16.5a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0zM15 16.5a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0z"/>
                                <path d="M3 4a1 1 0 00-1 1v10a1 1 0 001 1h1.05a2.5 2.5 0 014.9 0H10a1 1 0 001-1V5a1 1 0 00-1-1H3zM14 7a1 1 0 00-1 1v6.05A2.5 2.5 0 0115.95 16H17a1 1 0 001-1v-5a1 1 0 00-.293-.707l-2-2A1 1 0 0015 7h-1z"/>
                            </svg>
                            <span id="clienteVehiculo"></span>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-lg shadow-md p-6">
                    <h3 class="text-lg font-bold text-gray-800 mb-4">Método de Pago</h3>
                    
                    <select name="metodo_pago" required
                            class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 mb-4">
                        <option value="efectivo">Efectivo</option>
                        <option value="tarjeta">Tarjeta</option>
                        <option value="transferencia">Transferencia</option>
                        <option value="credito">Crédito</option>
                    </select>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Descuento (Q)</label>
                        <input type="number" name="descuento" id="descuento" step="0.01" min="0" value="0"
                               class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500">
                    </div>
                </div>

                <div class="bg-white rounded-lg shadow-md p-6">
                    <h3 class="text-lg font-bold text-gray-800 mb-4">Notas</h3>
                    <textarea name="notas" rows="3" placeholder="Notas adicionales sobre la venta..."
                              class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500"></textarea>
                </div>

            </div>

            <!-- Columna derecha - Productos -->
            <div class="lg:col-span-2 space-y-6">
                
                <!-- Buscador de productos -->
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h3 class="text-lg font-bold text-gray-800 mb-4">Agregar Productos</h3>
                    
                    <div class="mb-4">
                        <input type="text" id="buscarProducto" placeholder="Buscar producto por nombre o código..."
                               class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500">
                    </div>

                    <div id="listaProductos" class="grid grid-cols-1 md:grid-cols-2 gap-3 max-h-96 overflow-y-auto">
                        @foreach($productos as $producto)
                        <div class="producto-item border border-gray-200 rounded-lg p-3 hover:border-blue-500 cursor-pointer transition"
                             data-id="{{ $producto->id }}"
                             data-nombre="{{ $producto->nombre }}"
                             data-codigo="{{ $producto->codigo }}"
                             data-precio="{{ $producto->precio_venta }}"
                             data-stock="{{ $producto->stock_actual }}">
                            <div class="flex justify-between items-start mb-2">
                                <div class="flex-1">
                                    <p class="font-semibold text-sm text-gray-800">{{ $producto->nombre }}</p>
                                    <p class="text-xs text-gray-500">{{ $producto->codigo }}</p>
                                </div>
                                <span class="text-xs px-2 py-1 rounded-full {{ $producto->stock_actual > $producto->stock_minimo ? 'bg-green-100 text-green-800' : 'bg-orange-100 text-orange-800' }}">
                                    Stock: {{ $producto->stock_actual }}
                                </span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-sm font-bold text-green-600">Q{{ number_format($producto->precio_venta, 2) }}</span>
                                <button type="button" class="btn-agregar bg-blue-600 text-white px-3 py-1 rounded text-xs hover:bg-blue-700">
                                    Agregar
                                </button>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>

                <!-- Carrito de compra -->
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h3 class="text-lg font-bold text-gray-800 mb-4">Productos en la Venta</h3>
                    
                    <div id="carritoVacio" class="text-center py-12 text-gray-500">
                        <svg class="w-16 h-16 mx-auto mb-4 text-gray-300" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M3 1a1 1 0 000 2h1.22l.305 1.222a.997.997 0 00.01.042l1.358 5.43-.893.892C3.74 11.846 4.632 14 6.414 14H15a1 1 0 000-2H6.414l1-1H14a1 1 0 00.894-.553l3-6A1 1 0 0017 3H6.28l-.31-1.243A1 1 0 005 1H3zM16 16.5a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0zM6.5 18a1.5 1.5 0 100-3 1.5 1.5 0 000 3z"/>
                        </svg>
                        <p>No hay productos agregados</p>
                        <p class="text-sm">Busca y agrega productos para crear la venta</p>
                    </div>

                    <div id="carritoProductos" class="hidden space-y-3"></div>

                    <!-- Resumen -->
                    <div id="resumenVenta" class="hidden border-t pt-4 mt-4">
                        <div class="space-y-2">
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600">Subtotal:</span>
                                <span class="font-semibold" id="subtotalVenta">Q0.00</span>
                            </div>
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600">Descuento:</span>
                                <span class="font-semibold text-red-600" id="descuentoVenta">Q0.00</span>
                            </div>
                            <div class="flex justify-between text-lg border-t pt-2">
                                <span class="font-bold text-gray-800">Total:</span>
                                <span class="font-bold text-green-600 text-2xl" id="totalVenta">Q0.00</span>
                            </div>
                        </div>

                        <button type="submit" class="w-full mt-6 bg-green-600 text-white py-3 rounded-lg hover:bg-green-700 transition font-semibold text-lg">
                            Completar Venta
                        </button>
                    </div>
                </div>

            </div>

        </div>

    </form>

</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    let carrito = [];

    // Mostrar info del cliente al seleccionar
    document.getElementById('selectCliente').addEventListener('change', function() {
        const option = this.options[this.selectedIndex];
        if (this.value) {
            document.getElementById('clienteTelefono').textContent = option.dataset.telefono;
            document.getElementById('clienteEmail').textContent = option.dataset.email || 'No registrado';
            document.getElementById('clienteVehiculo').textContent = option.dataset.vehiculo;
            document.getElementById('infoCliente').classList.remove('hidden');
        } else {
            document.getElementById('infoCliente').classList.add('hidden');
        }
    });

    // Buscar productos
    document.getElementById('buscarProducto').addEventListener('input', function() {
        const busqueda = this.value.toLowerCase();
        document.querySelectorAll('.producto-item').forEach(item => {
            const nombre = item.dataset.nombre.toLowerCase();
            const codigo = item.dataset.codigo.toLowerCase();
            if (nombre.includes(busqueda) || codigo.includes(busqueda)) {
                item.classList.remove('hidden');
            } else {
                item.classList.add('hidden');
            }
        });
    });

    // Agregar producto al carrito
    document.querySelectorAll('.btn-agregar').forEach(btn => {
        btn.addEventListener('click', function() {
            const item = this.closest('.producto-item');
            const id = item.dataset.id;
            const nombre = item.dataset.nombre;
            const precio = parseFloat(item.dataset.precio);
            const stock = parseInt(item.dataset.stock);

            const existe = carrito.find(p => p.id == id);
            
            if (existe) {
                if (existe.cantidad < stock) {
                    existe.cantidad++;
                } else {
                    alert('No hay más stock disponible');
                    return;
                }
            } else {
                if (stock > 0) {
                    carrito.push({ id, nombre, precio, cantidad: 1, stock });
                } else {
                    alert('Producto sin stock');
                    return;
                }
            }

            actualizarCarrito();
        });
    });

    // Actualizar descuento
    document.getElementById('descuento').addEventListener('input', function() {
        actualizarCarrito();
    });

    function actualizarCarrito() {
        const contenedor = document.getElementById('carritoProductos');
        
        if (carrito.length === 0) {
            document.getElementById('carritoVacio').classList.remove('hidden');
            contenedor.classList.add('hidden');
            document.getElementById('resumenVenta').classList.add('hidden');
            return;
        }

        document.getElementById('carritoVacio').classList.add('hidden');
        contenedor.classList.remove('hidden');
        document.getElementById('resumenVenta').classList.remove('hidden');

        contenedor.innerHTML = '';
        let subtotal = 0;

        carrito.forEach((producto, index) => {
            const total = producto.precio * producto.cantidad;
            subtotal += total;

            const html = `
                <div class="flex items-center justify-between border border-gray-200 rounded-lg p-3">
                    <div class="flex-1">
                        <p class="font-semibold text-sm">${producto.nombre}</p>
                        <p class="text-xs text-gray-500">Q${producto.precio.toFixed(2)} c/u</p>
                    </div>
                    <div class="flex items-center gap-3">
                        <div class="flex items-center border border-gray-300 rounded">
                            <button type="button" class="px-2 py-1 hover:bg-gray-100" onclick="cambiarCantidad(${index}, -1)">-</button>
                            <input type="number" value="${producto.cantidad}" min="1" max="${producto.stock}" 
                                   class="w-16 text-center border-x border-gray-300 py-1" 
                                   onchange="actualizarCantidad(${index}, this.value)">
                            <button type="button" class="px-2 py-1 hover:bg-gray-100" onclick="cambiarCantidad(${index}, 1)">+</button>
                        </div>
                        <span class="font-bold text-green-600 w-24 text-right">Q${total.toFixed(2)}</span>
                        <button type="button" class="text-red-600 hover:text-red-800" onclick="eliminarProducto(${index})">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z"/>
                            </svg>
                        </button>
                    </div>
                    <input type="hidden" name="productos[${index}][id]" value="${producto.id}">
                    <input type="hidden" name="productos[${index}][cantidad]" value="${producto.cantidad}">
                    <input type="hidden" name="productos[${index}][precio]" value="${producto.precio}">
                </div>
            `;
            contenedor.innerHTML += html;
        });

        const descuento = parseFloat(document.getElementById('descuento').value) || 0;
        const total = subtotal - descuento;

        document.getElementById('subtotalVenta').textContent = 'Q' + subtotal.toFixed(2);
        document.getElementById('descuentoVenta').textContent = 'Q' + descuento.toFixed(2);
        document.getElementById('totalVenta').textContent = 'Q' + total.toFixed(2);
    }

    window.cambiarCantidad = function(index, cambio) {
        const producto = carrito[index];
        const nuevaCantidad = producto.cantidad + cambio;
        
        if (nuevaCantidad > 0 && nuevaCantidad <= producto.stock) {
            producto.cantidad = nuevaCantidad;
            actualizarCarrito();
        } else if (nuevaCantidad > producto.stock) {
            alert('No hay suficiente stock');
        }
    };

    window.actualizarCantidad = function(index, valor) {
        const cantidad = parseInt(valor);
        const producto = carrito[index];
        
        if (cantidad > 0 && cantidad <= producto.stock) {
            producto.cantidad = cantidad;
            actualizarCarrito();
        } else {
            alert('Cantidad no válida');
            actualizarCarrito();
        }
    };

    window.eliminarProducto = function(index) {
        if (confirm('¿Eliminar este producto?')) {
            carrito.splice(index, 1);
            actualizarCarrito();
        }
    };

    // Validar antes de enviar
    document.getElementById('formVenta').addEventListener('submit', function(e) {
        if (carrito.length === 0) {
            e.preventDefault();
            alert('Debes agregar al menos un producto a la venta');
        }
    });
});
</script>
@endsection
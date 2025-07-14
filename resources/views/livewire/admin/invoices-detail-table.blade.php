<!-- Encabezado -->
<head>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://unpkg.com/alpinejs" defer></script>
</head>

<div class="w-full py-8 px-4 sm:px-6 lg:px-8" x-data="detailTable()">
    {{-- Alertas --}}
    @if (session('success'))
        <script>
            Swal.fire({
                icon: "success",
                title: "¡Éxito!",
                text: "{{ session('success') }}",
                background: '#18181b',
                color: '#f4f4f5',
                iconColor: '#22c55e',
                confirmButtonColor: '#3b82f6',
            });
        </script>
    @endif

    @if ($errors->any())
        <script>
            Swal.fire({
                icon: 'error',
                title: 'Error',
                html: '<ul>@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>',
                background: '#18181b',
                color: '#f4f4f5',
                iconColor: '#ef4444',
                confirmButtonColor: '#3b82f6',
            });
        </script>
    @endif

    <!-- Contenedor -->
    <div class="w-full bg-zinc-900 rounded-xl shadow-2xl p-6 border border-zinc-800">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold text-white">Detalles de Facturas</h1>
            {{-- Exportaciones opcionales --}}
            
            <div class="space-x-2">
                <a href="{{ route('admin.invoices_detail.export-pdf') }}" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">Exportar PDF</a>
                <a href="{{ route('admin.invoices_detail.export-excel') }}" class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700">Exportar Excel</a>
            </div>
           
        </div>

        <!-- Tabla -->
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead class="bg-zinc-800">
                    <tr>
                        <th class="px-4 py-3 text-sm text-zinc-300">#</th>
                        <th class="px-4 py-3 text-sm text-zinc-300">Factura</th>
                        <th class="px-4 py-3 text-sm text-zinc-300">Servicio</th>
                        <th class="px-4 py-3 text-sm text-zinc-300">Producto</th>
                        <th class="px-4 py-3 text-sm text-zinc-300">Cantidad</th>
                        <th class="px-4 py-3 text-sm text-zinc-300">P. Unitario</th>
                        <th class="px-4 py-3 text-sm text-zinc-300">Subtotal</th>
                        <th class="px-4 py-3 text-sm text-zinc-300">Total</th>
                        <th class="px-4 py-3 text-sm text-right text-zinc-300">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-zinc-800">
                    @foreach ($details as $detail)
                        <tr>
                            <td class="px-4 py-4 text-sm text-zinc-300">{{ $loop->iteration }}</td>
                            <td class="px-4 py-4 text-sm text-zinc-300">#{{ $detail->invoice->id ?? '-' }}</td>
                            <td class="px-4 py-4 text-sm text-zinc-300">{{ $detail->service->nombre ?? '-' }}</td>
                            <td class="px-4 py-4 text-sm text-zinc-300">{{ $detail->product->nombre ?? '-' }}</td>
                            <td class="px-4 py-4 text-sm text-zinc-300">{{ $detail->cantidad }}</td>
                            <td class="px-4 py-4 text-sm text-zinc-300">S/ {{ number_format($detail->precio_unitario, 2) }}</td>
                            <td class="px-4 py-4 text-sm text-zinc-300">S/ {{ number_format($detail->sub_total, 2) }}</td>
                            <td class="px-4 py-4 text-sm text-zinc-300">S/ {{ number_format($detail->total, 2) }}</td>
                            <td class="px-4 py-4 text-sm text-right">
                                <button
    @click="abrirModal(
        {{ $detail->id }},
        {{ $detail->cantidad }},
        {{ $detail->precio_unitario }},
        {{ $detail->sub_total }},
        {{ $detail->total }}
    )"
    class="text-blue-500 hover:text-blue-400 mr-3"
>
    ✏️
</button>

                                <form id="delete-form-{{ $detail->id }}" action="{{ route('admin.invoices_detail.destroy', $detail->id) }}" method="POST" class="hidden">
                                    @csrf @method('DELETE')
                                </form>
                                <button onclick="confirmDelete({{ $detail->id }})" class="text-red-500 hover:text-red-400">🗑️</button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Paginación -->
        @if ($details->hasPages())
            <div class="mt-6">{{ $details->links() }}</div>
        @endif

        <!-- Modal de edición -->
<template x-teleport="body">
    <div x-show="isOpen" x-cloak class="fixed inset-0 z-50 overflow-y-auto">
        <div class="flex items-center justify-center min-h-screen px-4">
            <div class="fixed inset-0 bg-black opacity-75" @click="cerrarModal()"></div>
            <div class="bg-zinc-900 border border-zinc-800 rounded-lg w-full max-w-2xl mx-auto mt-12 p-6 shadow-xl text-white">
                <form :action="'/admin/detalle-factura/' + currentId" method="POST">
                    @csrf
                    @method('PUT')

                    <h3 class="text-xl font-bold mb-4">Editar Detalle de Factura</h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm mb-1">Cantidad</label>
                            <input type="number" min="1" x-model="currentCantidad"
                                   @input="recalcular()"
                                   name="cantidad"
                                   class="w-full px-4 py-2 bg-zinc-800 border border-zinc-700 rounded-lg text-white">
                        </div>

                        <div>
                            <label class="block text-sm mb-1">Precio Unitario</label>
                            <input type="number" step="0.01" min="0" x-model="currentPrecio"
                                   @input="recalcular()"
                                   name="precio_unitario"
                                   class="w-full px-4 py-2 bg-zinc-800 border border-zinc-700 rounded-lg text-white">
                        </div>

                        <div>
                            <label class="block text-sm mb-1">Subtotal</label>
                            <input type="number" step="0.01" min="0" x-model="currentSubtotal" name="sub_total"
                                   class="w-full px-4 py-2 bg-zinc-800 border border-zinc-700 rounded-lg text-white" readonly>
                        </div>

                        <div>
                            <label class="block text-sm mb-1">Total</label>
                            <input type="number" step="0.01" min="0" x-model="currentTotal" name="total"
                                   class="w-full px-4 py-2 bg-zinc-800 border border-zinc-700 rounded-lg text-white" readonly>
                        </div>
                    </div>

                    <div class="mt-6 flex justify-end gap-4">
                        <button type="button" @click="cerrarModal" class="text-zinc-300 hover:text-white">Cancelar</button>
                        <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">Guardar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</template>

    </div>
</div>

<!-- Confirmación con SweetAlert -->
<script>
    function confirmDelete(id) {
        Swal.fire({
            title: '¿Eliminar detalle?',
            text: "Esta acción no se puede deshacer.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3b82f6',
            cancelButtonColor: '#6b7280',
            confirmButtonText: 'Sí, eliminar',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('delete-form-' + id).submit();
            }
        });
    }

    function detailTable() {
        return {
            isOpen: false,
            currentId: null,
            currentCantidad: 1,
            currentPrecio: 0,
            currentSubtotal: 0,
            currentTotal: 0,

            abrirModal(id, cantidad, precio, subtotal, total) {
                this.currentId = id;
                this.currentCantidad = cantidad;
                this.currentPrecio = precio;
                this.currentSubtotal = subtotal;
                this.currentTotal = total;
                this.isOpen = true;
            },

            cerrarModal() {
                this.isOpen = false;
            },

            recalcular() {
                const cantidad = parseFloat(this.currentCantidad) || 0;
                const precio = parseFloat(this.currentPrecio) || 0;
                this.currentSubtotal = (cantidad * precio).toFixed(2);
                this.currentTotal = (cantidad * precio).toFixed(2);
            }
        };
    }
</script>


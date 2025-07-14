<head>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://unpkg.com/alpinejs" defer></script>
</head>

<div class="w-full py-8 px-4 sm:px-6 lg:px-8" x-data="invoiceTable()">
    {{-- Notificaciones --}}
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
                customClass: { popup: 'rounded-lg shadow-lg' }
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
                customClass: { popup: 'rounded-lg shadow-lg text-left' }
            });
        </script>
    @endif

    <div class="w-full bg-zinc-900 rounded-xl shadow-2xl p-6 border border-zinc-800">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold text-white">Lista de Facturas</h1>
            <div class="space-x-2">
                <a href="{{ route('admin.invoices.export-pdf') }}"
                    class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                    Exportar PDF
                </a>
                <a href="{{ route('admin.invoices.export-excel') }}"
                    class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700">
                    Exportar Excel
                </a>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-zinc-800">
                    <tr>
                        <th class="px-4 py-3 text-left text-sm text-zinc-300 uppercase">#</th>
                        <th class="px-4 py-3 text-left text-sm text-zinc-300 uppercase">Cliente</th>
                        <th class="px-4 py-3 text-left text-sm text-zinc-300 uppercase">Fecha</th>
                        <th class="px-4 py-3 text-left text-sm text-zinc-300 uppercase">Subtotal</th>
                        <th class="px-4 py-3 text-left text-sm text-zinc-300 uppercase">Impuesto</th>
                        <th class="px-4 py-3 text-left text-sm text-zinc-300 uppercase">Total</th>
                        <th class="px-4 py-3 text-right text-sm text-zinc-300 uppercase">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-zinc-800">
                    @foreach ($invoices as $invoice)
                        <tr>
                            <td class="px-4 py-4 text-sm text-zinc-300">{{ $loop->iteration }}</td>
                            <td class="px-4 py-4 text-sm text-zinc-300">{{ $invoice->customer->nombres }} {{ $invoice->customer->apellidos }}</td>
                            <td class="px-4 py-4 text-sm text-zinc-300">{{ \Carbon\Carbon::parse($invoice->fecha)->format('d/m/Y') }}</td>
                            <td class="px-4 py-4 text-sm text-zinc-300">S/. {{ number_format($invoice->sub_total, 2) }}</td>
                            <td class="px-4 py-4 text-sm text-zinc-300">S/. {{ number_format($invoice->impuesto, 2) }}</td>
                            <td class="px-4 py-4 text-sm text-zinc-300">S/. {{ number_format($invoice->total_metodo_pago, 2) }}</td>
                            <td class="px-4 py-4 text-sm text-right">
                                <button
                                    @click="openModal(
                                        {{ $invoice->id }},
                                        '{{ $invoice->customer_id }}',
                                        '{{ $invoice->fecha }}',
                                        '{{ $invoice->sub_total }}',
                                        '{{ $invoice->impuesto }}',
                                        '{{ $invoice->total_metodo_pago }}'
                                    )"
                                    class="text-blue-500 hover:text-blue-400 mr-3">✏️</button>

                                <button onclick="confirmDelete({{ $invoice->id }})"
                                        class="text-red-500 hover:text-red-400">🗑️</button>

                                <form id="delete-form-{{ $invoice->id }}"
                                      action="{{ route('admin.invoices.destroy', $invoice->id) }}"
                                      method="POST" class="hidden">
                                    @csrf
                                    @method('DELETE')
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        @if ($invoices->hasPages())
            <div class="mt-6">{{ $invoices->links() }}</div>
        @endif
    </div>

    {{-- Modal --}}
    <template x-teleport="body">
        <div x-show="isOpen" x-cloak class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-75">
            <div class="bg-zinc-900 p-8 rounded-lg border border-zinc-700 w-full max-w-3xl">
                <form :action="'/admin/invoices/' + currentId" method="POST">
                    @csrf
                    @method('PUT')
                    <h3 class="text-white text-xl font-semibold mb-6">Editar Factura</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm text-zinc-300 mb-1">Cliente</label>
                            <select x-model="currentCustomerId" name="customer_id"
                                    class="w-full px-4 py-2 bg-zinc-800 border border-zinc-700 rounded text-white" required>
                                @foreach ($customers as $customer)
                                    <option :selected="currentCustomerId == {{ $customer->id }}" value="{{ $customer->id }}">
                                        {{ $customer->nombres }} {{ $customer->apellidos }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm text-zinc-300 mb-1">Fecha</label>
                            <input type="date" x-model="currentFecha" name="fecha"
                                class="w-full px-4 py-2 bg-zinc-800 border border-zinc-700 rounded text-white" required>
                        </div>
                        <div>
                            <label class="block text-sm text-zinc-300 mb-1">Subtotal</label>
                            <input type="number" step="0.01" x-model="currentSubtotal" name="sub_total"
                                class="w-full px-4 py-2 bg-zinc-800 border border-zinc-700 rounded text-white" required>
                        </div>
                        <div>
                            <label class="block text-sm text-zinc-300 mb-1">Impuesto</label>
                            <input type="number" step="0.01" x-model="currentImpuesto" name="impuesto"
                                class="w-full px-4 py-2 bg-zinc-800 border border-zinc-700 rounded text-white" required>
                        </div>
                        <div>
                            <label class="block text-sm text-zinc-300 mb-1">Total</label>
                            <input type="number" step="0.01" x-model="currentTotal" name="total_metodo_pago"
                                class="w-full px-4 py-2 bg-zinc-800 border border-zinc-700 rounded text-white" required>
                        </div>
                    </div>
                    <div class="mt-6 flex justify-end space-x-4">
                        <button type="button" @click="closeModal" class="text-zinc-300 hover:text-white">Cancelar</button>
                        <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">Guardar</button>
                    </div>
                </form>
            </div>
        </div>
    </template>
</div>

<script>
    function confirmDelete(id) {
        Swal.fire({
            title: '¿Eliminar factura?',
            text: 'Esta acción no se puede deshacer.',
            icon: 'warning',
            background: '#18181b',
            color: '#f4f4f5',
            iconColor: '#ef4444',
            showCancelButton: true,
            confirmButtonColor: '#3b82f6',
            cancelButtonColor: '#6b7280',
            confirmButtonText: 'Sí, eliminar',
            cancelButtonText: 'Cancelar',
            customClass: { popup: 'rounded-lg shadow-lg' }
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('delete-form-' + id).submit();
            }
        });
    }

    function invoiceTable() {
        return {
            isOpen: false,
            currentId: null,
            currentCustomerId: '',
            currentFecha: '',
            currentSubtotal: '',
            currentImpuesto: '',
            currentTotal: '',

            openModal(id, customer_id, fecha, sub_total, impuesto, total) {
                this.currentId = id;
                this.currentCustomerId = customer_id;
                this.currentFecha = fecha;
                this.currentSubtotal = sub_total;
                this.currentImpuesto = impuesto;
                this.currentTotal = total;
                this.isOpen = true;
                document.body.classList.add('overflow-hidden');
            },

            closeModal() {
                this.isOpen = false;
                document.body.classList.remove('overflow-hidden');
            }
        }
    }
</script>

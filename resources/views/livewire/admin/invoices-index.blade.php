<head>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>

<div class="w-full py-8 px-4 sm:px-6 lg:px-8">
    {{-- Alerta --}}
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
                customClass: {
                    popup: 'rounded-lg shadow-lg'
                }
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
                customClass: {
                    popup: 'rounded-lg shadow-lg text-left'
                }
            });
        </script>
    @endif

    <div class="w-full bg-zinc-900 rounded-xl shadow-2xl overflow-hidden p-6 border border-zinc-800">
        <h1 class="text-2xl font-bold text-white mb-6">
            Registrar Nueva Factura
        </h1>

        <form action="{{ route('admin.invoices.store') }}" method="POST" class="space-y-6">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Cliente -->
                <div>
                    <label for="customer_id" class="text-sm text-zinc-300 font-medium mb-1 block">
                        Cliente <span class="text-red-500">*</span>
                    </label>
                    <select id="customer_id" name="customer_id" class="w-full px-4 py-3 bg-zinc-800 border border-zinc-700 rounded-lg text-white" required>
                        <option value="">Seleccione un cliente</option>
                        @foreach($customers as $customer)
                            <option value="{{ $customer->id }}">{{ $customer->nombres }} {{ $customer->apellidos }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Fecha -->
                <div>
                    <label for="fecha" class="text-sm text-zinc-300 font-medium mb-1 block">
                        Fecha <span class="text-red-500">*</span>
                    </label>
                    <input type="date" id="fecha" name="fecha"
                        class="w-full px-4 py-3 bg-zinc-800 border border-zinc-700 rounded-lg text-white"
                        required>
                </div>

                <!-- Sub Total -->
                <div>
                    <label for="sub_total" class="text-sm text-zinc-300 font-medium mb-1 block">
                        Sub Total (S/.) <span class="text-red-500">*</span>
                    </label>
                    <input type="number" step="0.01" id="sub_total" name="sub_total"
                        class="w-full px-4 py-3 bg-zinc-800 border border-zinc-700 rounded-lg text-white" required>
                </div>

                <!-- Impuesto -->
                <div>
                    <label for="impuesto" class="text-sm text-zinc-300 font-medium mb-1 block">
                        Impuesto (S/.) <span class="text-red-500">*</span>
                    </label>
                    <input type="number" step="0.01" id="impuesto" name="impuesto"
                        class="w-full px-4 py-3 bg-zinc-800 border border-zinc-700 rounded-lg text-white" required>
                </div>

                <!-- Total -->
                <div>
                    <label for="total_metodo_pago" class="text-sm text-zinc-300 font-medium mb-1 block">
                        Total a Pagar (S/.) <span class="text-red-500">*</span>
                    </label>
                    <input type="number" step="0.01" id="total_metodo_pago" name="total_metodo_pago"
                        class="w-full px-4 py-3 bg-zinc-800 border border-zinc-700 rounded-lg text-white" required>
                </div>
            </div>

            <!-- Nota -->
            <div class="text-sm text-zinc-500 mb-6">
                Campos marcados con <span class="text-red-500 font-bold">*</span> son obligatorios
            </div>

            <!-- Botón de acción -->
            <div class="flex justify-end">
                <button type="submit"
                    class="px-6 py-3 bg-blue-600 text-white font-medium rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 focus:ring-offset-zinc-900 transition-all duration-200 shadow-lg hover:shadow-xl">
                    Registrar Factura
                </button>
            </div>
        </form>
    </div>
</div>

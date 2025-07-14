<head>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>

<div class="w-full py-8 px-4 sm:px-6 lg:px-8">
    {{-- Alerta de éxito --}}
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

    {{-- Alerta de errores --}}
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

    <div class="w-full bg-zinc-900 rounded-xl shadow-2xl overflow-hidden p-6 border border-zinc-800">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold text-white">Registrar Detalle de Factura</h1>
        </div>

        <form action="{{ route('admin.invoices_detail.store') }}" method="POST" class="space-y-6">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                <!-- Factura -->
                <div>
                    <label for="invoices_id" class="text-sm text-zinc-300 font-medium mb-1 block">
                        Factura <span class="text-red-500">*</span>
                    </label>
                    <select id="invoices_id" name="invoices_id"
                        class="w-full px-4 py-3 bg-zinc-800 border border-zinc-700 rounded-lg text-white" required>
                        @foreach ($invoices as $invoice)
                            <option value="{{ $invoice->id }}">#{{ $invoice->id }} - {{ $invoice->fecha }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Servicio -->
                <div>
                    <label for="service_id" class="text-sm text-zinc-300 font-medium mb-1 block">
                        Servicio (opcional)
                    </label>
                    <select id="service_id" name="service_id"
                        class="w-full px-4 py-3 bg-zinc-800 border border-zinc-700 rounded-lg text-white">
                        <option value="">-- Seleccionar --</option>
                        @foreach ($services as $service)
                            <option value="{{ $service->id }}">{{ $service->nombre }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Producto -->
                <div>
                    <label for="product_id" class="text-sm text-zinc-300 font-medium mb-1 block">
                        Producto (opcional)
                    </label>
                    <select id="product_id" name="product_id"
                        class="w-full px-4 py-3 bg-zinc-800 border border-zinc-700 rounded-lg text-white">
                        <option value="">-- Seleccionar --</option>
                        @foreach ($products as $product)
                            <option value="{{ $product->id }}">{{ $product->nombre }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Cantidad -->
                <div>
                    <label for="cantidad" class="text-sm text-zinc-300 font-medium mb-1 block">
                        Cantidad <span class="text-red-500">*</span>
                    </label>
                    <input type="number" id="cantidad" name="cantidad" min="1"
                        class="w-full px-4 py-3 bg-zinc-800 border border-zinc-700 rounded-lg text-white" required>
                </div>

                <!-- Precio Unitario -->
                <div>
                    <label for="precio_unitario" class="text-sm text-zinc-300 font-medium mb-1 block">
                        Precio Unitario <span class="text-red-500">*</span>
                    </label>
                    <input type="number" step="0.01" min="0" id="precio_unitario" name="precio_unitario"
                        class="w-full px-4 py-3 bg-zinc-800 border border-zinc-700 rounded-lg text-white" required>
                </div>

                <!-- Sub Total -->
                <div>
                    <label for="sub_total" class="text-sm text-zinc-300 font-medium mb-1 block">
                        Sub Total <span class="text-red-500">*</span>
                    </label>
                    <input type="number" step="0.01" min="0" id="sub_total" name="sub_total"
                        class="w-full px-4 py-3 bg-zinc-800 border border-zinc-700 rounded-lg text-white" required readonly>
                </div>

                <!-- Total -->
                <div>
                    <label for="total" class="text-sm text-zinc-300 font-medium mb-1 block">
                        Total <span class="text-red-500">*</span>
                    </label>
                    <input type="number" step="0.01" min="0" id="total" name="total"
                        class="w-full px-4 py-3 bg-zinc-800 border border-zinc-700 rounded-lg text-white" required readonly>
                </div>
            </div>

            <div class="text-sm text-zinc-500">
                Campos marcados con <span class="text-red-500 font-bold">*</span> son obligatorios
            </div>

            <div class="flex justify-end pt-4">
                <button type="submit"
                    class="px-6 py-3 bg-blue-600 text-white font-medium rounded-lg hover:bg-blue-700 transition-all duration-200 shadow-lg hover:shadow-xl">
                    Registrar Detalle
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    $(document).ready(function () {
        function calcularTotales() {
            let cantidad = parseFloat($('#cantidad').val()) || 0;
            let precio = parseFloat($('#precio_unitario').val()) || 0;
            let subtotal = cantidad * precio;
            $('#sub_total').val(subtotal.toFixed(2));
            $('#total').val(subtotal.toFixed(2)); // si hay impuestos, puedes sumar aquí
        }

        $('#cantidad, #precio_unitario').on('input', calcularTotales);
    });
</script>

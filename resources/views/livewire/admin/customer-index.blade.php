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

    @if ($errors->any()))
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
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold text-white">
                Registrar Nuevo Cliente
            </h1>
        </div>

        <form action="{{ route('admin.customer.store') }}" method="POST" class="space-y-6">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Tipo de documento -->
                <div>
                    <label for="tipo_documento" class="text-sm text-zinc-300 font-medium mb-1 block">
                        Tipo de Documento <span class="text-red-500">*</span>
                    </label>
                    <select id="tipo_documento" name="tipo_documento"
                        class="w-full px-4 py-3 bg-zinc-800 border border-zinc-700 rounded-lg text-white" required>
                        <option value="DNI">DNI</option>
                        <option value="RUC">RUC</option>
                        <option value="CE">Carné de Extranjería</option>
                        <option value="PASAPORTE">Pasaporte</option>
                    </select>
                </div>

                <!-- DNI -->
                <div>
                    <label for="dni" class="text-sm text-zinc-300 font-medium mb-1 block">
                        Número de Documento <span class="text-red-500">*</span>
                    </label>
                    <div class="flex gap-2">
                        <input type="text" id="dni" name="dni" maxlength="20"
                            class="w-full px-4 py-3 bg-zinc-800 border border-zinc-700 rounded-lg text-white"
                            placeholder="Ingrese el número de documento" required
                            oninput="this.value = this.value.replace(/[^0-9]/g, '')">
                        <button type="button" id="consultar-dni"
                            class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg shadow">
                            Consultar
                        </button>
                    </div>
                </div>

                <!-- Nombres -->
                <div>
                    <label for="nombres" class="text-sm text-zinc-300 font-medium mb-1 block">
                        Nombres <span class="text-red-500">*</span>
                    </label>
                    <input type="text" id="nombres" name="nombres"
                        class="w-full px-4 py-3 bg-zinc-800 border border-zinc-700 rounded-lg text-white"
                        required maxlength="100">
                </div>

                <!-- Apellidos -->
                <div>
                    <label for="apellidos" class="text-sm text-zinc-300 font-medium mb-1 block">
                        Apellidos <span class="text-red-500">*</span>
                    </label>
                    <input type="text" id="apellidos" name="apellidos"
                        class="w-full px-4 py-3 bg-zinc-800 border border-zinc-700 rounded-lg text-white"
                        required maxlength="100">
                </div>

                <!-- Dirección -->
                <div>
                    <label for="direccion" class="text-sm text-zinc-300 font-medium mb-1 block">
                        Dirección
                    </label>
                    <input type="text" id="direccion" name="direccion"
                        class="w-full px-4 py-3 bg-zinc-800 border border-zinc-700 rounded-lg text-white"
                        maxlength="200">
                </div>

                <!-- Teléfono -->
                <div>
                    <label for="telefono" class="text-sm text-zinc-300 font-medium mb-1 block">
                        Teléfono
                    </label>
                    <input type="text" id="telefono" name="telefono"
                        class="w-full px-4 py-3 bg-zinc-800 border border-zinc-700 rounded-lg text-white"
                        maxlength="20">
                </div>

                <!-- Email -->
                <div>
                    <label for="email" class="text-sm text-zinc-300 font-medium mb-1 block">
                        Email
                    </label>
                    <input type="email" id="email" name="email"
                        class="w-full px-4 py-3 bg-zinc-800 border border-zinc-700 rounded-lg text-white"
                        maxlength="100">
                </div>

                <!-- Fecha de Registro -->
                <div>
                    <label for="fecha_registro" class="text-sm text-zinc-300 font-medium mb-1 block">
                        Fecha de Registro <span class="text-red-500">*</span>
                    </label>
                    <input type="date" id="fecha_registro" name="fecha_registro"
                        class="w-full px-4 py-3 bg-zinc-800 border border-zinc-700 rounded-lg text-white"
                        required>
                </div>
            </div>

            <!-- Nota de campos obligatorios -->
            <div class="text-sm text-zinc-500">
                Campos marcados con <span class="text-red-500 font-bold">*</span> son obligatorios
            </div>

            <!-- Botón de acción principal -->
            <div class="flex justify-end pt-4">
                <button type="submit"
                    class="px-6 py-3 bg-blue-600 text-white font-medium rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 focus:ring-offset-zinc-900 transition-all duration-200 shadow-lg hover:shadow-xl">
                    Registrar Cliente
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    $(document).ready(function () {
        $('#consultar-dni').on('click', function () {
            const dni = $('#dni').val();
            const tipoDocumento = $('#tipo_documento').val();

            if (tipoDocumento === 'DNI' && !dni.match(/^\d{8}$/)) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'El DNI debe tener exactamente 8 dígitos',
                    background: '#18181b',
                    color: '#f4f4f5',
                    iconColor: '#ef4444',
                });
                return;
            }

            if (tipoDocumento === 'RUC' && !dni.match(/^\d{11}$/)) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'El RUC debe tener exactamente 11 dígitos',
                    background: '#18181b',
                    color: '#f4f4f5',
                    iconColor: '#ef4444',
                });
                return;
            }

            $.ajax({
                url: '{{ route('admin.customer.consultar-dni') }}',
                method: 'GET',
                data: {
                    dni: dni,
                    tipo_documento: tipoDocumento
                },
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                success: function (data) {
                    if (data.error) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: data.error,
                            background: '#18181b',
                            color: '#f4f4f5',
                            iconColor: '#ef4444',
                        });
                    } else {
                        $('#nombres').val(data.nombres || '');
                        $('#apellidos').val(data.apellidos || '');
                        Swal.fire({
                            icon: 'success',
                            title: '¡Consulta exitosa!',
                            text: 'Datos cargados automáticamente.',
                            background: '#18181b',
                            color: '#f4f4f5',
                            iconColor: '#22c55e',
                        });
                    }
                },
                error: function (xhr) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: xhr.responseJSON?.error || 'Error al consultar la API',
                        background: '#18181b',
                        color: '#f4f4f5',
                        iconColor: '#ef4444',
                    });
                }
            });
        });
    });
</script>
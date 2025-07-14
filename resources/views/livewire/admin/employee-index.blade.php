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
            Registrar Nuevo Empleado
        </h1>

        <form action="{{ route('admin.employee.store') }}" method="POST" class="space-y-6">
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
                        <option value="CE">Carné de Extranjería</option>
                        <option value="PASAPORTE">Pasaporte</option>
                    </select>
                </div>

                <!-- DNI -->
                <div>
                    <label for="dni" class="text-sm text-zinc-300 font-medium mb-1 block">
                        DNI <span class="text-red-500">*</span>
                    </label>
                    <div class="flex gap-2">
                        <input type="text" id="dni" name="dni" maxlength="8"
                            class="w-full px-4 py-3 bg-zinc-800 border border-zinc-700 rounded-lg text-white"
                            placeholder="Ej: 12345678" required pattern="\d{8}"
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

                <!-- Puesto -->
                <div>
                    <label for="puesto" class="text-sm text-zinc-300 font-medium mb-1 block">
                        Puesto <span class="text-red-500">*</span>
                    </label>
                    <select id="puesto" name="puesto"
                        class="w-full px-4 py-3 bg-zinc-800 border border-zinc-700 rounded-lg text-white"
                        required>
                        <option value="" disabled selected>Seleccione un puesto</option>
                        <option value="Veterinario">Veterinario</option>
                        <option value="Asistente Veterinario">Asistente Veterinario</option>
                        <option value="Recepcionista">Recepcionista</option>
                        <option value="Técnico en Laboratorio">Técnico en Laboratorio</option>
                        <option value="Encargado de Limpieza">Encargado de Limpieza</option>
                        <option value="Administrador">Administrador</option>
                        <option value="Peluquero Canino/Felino">Peluquero Canino/Felino</option>
                        <option value="Encargado de Almacén">Encargado de Almacén</option>
                        <option value="Especialista en Animales Exóticos">Especialista en Animales Exóticos</option>
                        <option value="Gerente de Clínica">Gerente de Clínica</option>
                    </select>
                    @error('puesto')
                        <p class="mt-1 text-sm text-red-500 font-medium">{{ $message }}</p>
                    @enderror
                </div>


                <!-- Fecha de contratación -->
                <div>
                    <label for="fecha_contratacion" class="text-sm text-zinc-300 font-medium mb-1 block">
                        Fecha de Contratación <span class="text-red-500">*</span>
                    </label>
                    <input type="date" id="fecha_contratacion" name="fecha_contratacion"
                        class="w-full px-4 py-3 bg-zinc-800 border border-zinc-700 rounded-lg text-white" required>
                </div>

                <!-- Email -->
                <div>
                    <label for="email" class="text-sm text-zinc-300 font-medium mb-1 block">
                        Correo Electrónico
                    </label>
                    <input type="email" id="email" name="email"
                        class="w-full px-4 py-3 bg-zinc-800 border border-zinc-700 rounded-lg text-white">
                </div>

                <!-- Teléfono -->
                <div>
                    <label for="telefono" class="text-sm text-zinc-300 font-medium mb-1 block">
                        Teléfono
                    </label>
                    <input type="text" id="telefono" name="telefono"
                        class="w-full px-4 py-3 bg-zinc-800 border border-zinc-700 rounded-lg text-white">
                </div>

                <!-- Dirección -->
                <div>
                    <label for="direccion" class="text-sm text-zinc-300 font-medium mb-1 block">
                        Dirección
                    </label>
                    <input type="text" id="direccion" name="direccion"
                        class="w-full px-4 py-3 bg-zinc-800 border border-zinc-700 rounded-lg text-white">
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
                    Registrar Empleado
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    $(document).ready(function () {
        $('#consultar-dni').on('click', function () {
            const dni = $('#dni').val();
            const tipo_documento = $('#tipo_documento').val();

            if (!dni.match(/^\d{8}$/)) {
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

            $.ajax({
                url: '{{ route('admin.employee.consultar-dni') }}',
                method: 'GET',
                data: {
                    dni: dni,
                    tipo_documento: tipo_documento
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

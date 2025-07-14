<head>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
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
                customClass: {
                    popup: 'rounded-lg shadow-lg'
                }
            });
        </script>
    @endif

    {{-- Errores de validación --}}
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
            Registrar Nueva Cita
        </h1>

        <form action="{{ route('admin.appointment.store') }}" method="POST" class="space-y-6">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                {{-- Mascota --}}
                <div>
                    <label for="pet_id" class="text-sm text-zinc-300 font-medium mb-1 block">
                        Mascota <span class="text-red-500">*</span>
                    </label>
                    <select id="pet_id" name="pet_id"
                            class="w-full px-4 py-3 bg-zinc-800 border border-zinc-700 rounded-lg text-white" required>
                        <option value="" disabled selected>Seleccione una mascota</option>
                        @foreach ($pets as $pet)
                            <option value="{{ $pet->id }}">{{ $pet->nombre }}</option>
                        @endforeach
                    </select>
                </div>

                {{-- Veterinario --}}
                <div>
                    <label for="veterinary_id" class="text-sm text-zinc-300 font-medium mb-1 block">
                        Veterinario <span class="text-red-500">*</span>
                    </label>
                    <select id="veterinary_id" name="veterinary_id"
                            class="w-full px-4 py-3 bg-zinc-800 border border-zinc-700 rounded-lg text-white" required>
                        <option value="" disabled selected>Seleccione un veterinario</option>
                        @foreach ($veterinaries as $veterinary)
                            <option value="{{ $veterinary->id }}">{{ $veterinary->id }}</option>
                        @endforeach
                    </select>
                </div>

                {{-- Servicio --}}
                <div>
                    <label for="service_id" class="text-sm text-zinc-300 font-medium mb-1 block">
                        Servicio <span class="text-red-500">*</span>
                    </label>
                    <select id="service_id" name="service_id"
                            class="w-full px-4 py-3 bg-zinc-800 border border-zinc-700 rounded-lg text-white" required>
                        <option value="" disabled selected>Seleccione un servicio</option>
                        @foreach ($services as $service)
                            <option value="{{ $service->id }}">{{ $service->nombre }}</option>
                        @endforeach
                    </select>
                </div>

                {{-- Fecha y Hora --}}
                <div>
                    <label for="fecha_hora" class="text-sm text-zinc-300 font-medium mb-1 block">
                        Fecha y Hora <span class="text-red-500">*</span>
                    </label>
                    <input type="datetime-local" id="fecha_hora" name="fecha_hora"
                           class="w-full px-4 py-3 bg-zinc-800 border border-zinc-700 rounded-lg text-white" required>
                </div>

                {{-- Estado --}}
                <div>
                    <label for="estado" class="text-sm text-zinc-300 font-medium mb-1 block">
                        Estado <span class="text-red-500">*</span>
                    </label>
                    <select id="estado" name="estado"
                            class="w-full px-4 py-3 bg-zinc-800 border border-zinc-700 rounded-lg text-white" required>
                        <option value="pendiente">Pendiente</option>
                        <option value="confirmado">Confirmado</option>
                        <option value="completado">Completado</option>
                        <option value="cancelado">Cancelado</option>
                    </select>
                </div>

                {{-- Notas --}}
                <div class="md:col-span-2">
                    <label for="notas" class="text-sm text-zinc-300 font-medium mb-1 block">
                        Notas
                    </label>
                    <textarea id="notas" name="notas" rows="4"
                              class="w-full px-4 py-3 bg-zinc-800 border border-zinc-700 rounded-lg text-white"
                              placeholder="Observaciones adicionales sobre la cita..."></textarea>
                </div>
            </div>

            <div class="text-sm text-zinc-500 mb-6">
                Los campos marcados con <span class="text-red-500 font-bold">*</span> son obligatorios.
            </div>

            {{-- Botón --}}
            <div class="flex justify-end">
                <button type="submit"
                        class="px-6 py-3 bg-blue-600 text-white font-medium rounded-lg hover:bg-blue-700 shadow-lg">
                    Registrar Cita
                </button>
            </div>
        </form>
    </div>
</div>

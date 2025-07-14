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
            Registrar Nueva Mascota
        </h1>

        <form action="{{ route('admin.pet.store') }}" method="POST" class="space-y-6">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                {{-- Cliente --}}
                <div>
                    <label for="customer_id" class="text-sm text-zinc-300 font-medium mb-1 block">
                        Propietario <span class="text-red-500">*</span>
                    </label>
                    <select id="customer_id" name="customer_id"
                            class="w-full px-4 py-3 bg-zinc-800 border border-zinc-700 rounded-lg text-white"
                            required>
                        <option value="" disabled selected>Seleccione un cliente</option>
                        @foreach ($customers as $customer)
                            <option value="{{ $customer->id }}">{{ $customer->nombres }} {{ $customer->apellidos }}</option>
                        @endforeach
                    </select>
                </div>

                {{-- Raza --}}
                <div>
                    <label for="breed_id" class="text-sm text-zinc-300 font-medium mb-1 block">
                        Raza <span class="text-red-500">*</span>
                    </label>
                    <select id="breed_id" name="breed_id"
                            class="w-full px-4 py-3 bg-zinc-800 border border-zinc-700 rounded-lg text-white"
                            required>
                        <option value="" disabled selected>Seleccione una raza</option>
                        @foreach ($breeds as $breed)
                            <option value="{{ $breed->id }}">{{ $breed->nombre }}</option>
                        @endforeach
                    </select>
                </div>

                {{-- Nombre --}}
                <div>
                    <label for="nombre" class="text-sm text-zinc-300 font-medium mb-1 block">
                        Nombre de la Mascota <span class="text-red-500">*</span>
                    </label>
                    <input type="text" id="nombre" name="nombre"
                           class="w-full px-4 py-3 bg-zinc-800 border border-zinc-700 rounded-lg text-white"
                           placeholder="Ej: Max, Luna..." required>
                </div>

                {{-- Fecha de nacimiento --}}
                <div>
                    <label for="fecha_nacimiento" class="text-sm text-zinc-300 font-medium mb-1 block">
                        Fecha de Nacimiento
                    </label>
                    <input type="date" id="fecha_nacimiento" name="fecha_nacimiento"
                           class="w-full px-4 py-3 bg-zinc-800 border border-zinc-700 rounded-lg text-white">
                </div>

                {{-- Género --}}
                <div>
                    <label for="genero" class="text-sm text-zinc-300 font-medium mb-1 block">
                        Género <span class="text-red-500">*</span>
                    </label>
                    <select id="genero" name="genero"
                            class="w-full px-4 py-3 bg-zinc-800 border border-zinc-700 rounded-lg text-white"
                            required>
                        <option value="" disabled selected>Seleccione un género</option>
                        <option value="Macho">Macho</option>
                        <option value="Hembra">Hembra</option>
                    </select>
                </div>

                {{-- Color --}}
                <div>
                    <label for="color" class="text-sm text-zinc-300 font-medium mb-1 block">
                        Color
                    </label>
                    <input type="text" id="color" name="color"
                           class="w-full px-4 py-3 bg-zinc-800 border border-zinc-700 rounded-lg text-white"
                           placeholder="Ej: Negro, Blanco, Marrón...">
                </div>

                {{-- Peso --}}
                <div>
                    <label for="peso" class="text-sm text-zinc-300 font-medium mb-1 block">
                        Peso (kg)
                    </label>
                    <input type="number" id="peso" name="peso" min="0" step="0.01"
                           class="w-full px-4 py-3 bg-zinc-800 border border-zinc-700 rounded-lg text-white"
                           placeholder="Ej: 5.5">
                </div>

                {{-- Foto --}}
                <div class="md:col-span-2">
                    <label for="foto" class="text-sm text-zinc-300 font-medium mb-1 block">
                        Foto (nombre de archivo o URL)
                    </label>
                    <input type="text" id="foto" name="foto"
                           class="w-full px-4 py-3 bg-zinc-800 border border-zinc-700 rounded-lg text-white"
                           placeholder="Ej: mascota.jpg o https://...">
                </div>
            </div>

            {{-- Nota --}}
            <div class="text-sm text-zinc-500 mb-6">
                Los campos marcados con <span class="text-red-500 font-bold">*</span> son obligatorios.
            </div>

            {{-- Botón --}}
            <div class="flex justify-end">
                <button type="submit"
                        class="px-6 py-3 bg-blue-600 text-white font-medium rounded-lg hover:bg-blue-700 shadow-lg">
                    Registrar Mascota
                </button>
            </div>
        </form>
    </div>
</div>

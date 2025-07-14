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

    <div class="w-full bg-zinc-900 rounded-xl shadow-2xl overflow-hidden p-6 border border-zinc-800">
        <h1 class="text-2xl font-bold text-white mb-6">
            Registrar Historia Clínica
        </h1>

        <form action="{{ route('admin.history.store') }}" method="POST" class="space-y-6">
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
                            <option value="{{ $pet->id }}">{{ $pet->nombre }} ({{ $pet->customer->nombres }} {{ $pet->customer->apellidos }})</option>
                        @endforeach
                    </select>
                    @error('pet_id')
                        <p class="mt-1 text-sm text-red-500 font-medium">{{ $message }}</p>
                    @enderror
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
                            <option value="{{ $veterinary->id }}">
                                {{ $veterinary->employee->nombres }} {{ $veterinary->employee->apellidos }}
                            </option>
                        @endforeach
                    </select>
                    @error('veterinary_id')
                        <p class="mt-1 text-sm text-red-500 font-medium">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Fecha --}}
                <div>
                    <label for="fecha" class="text-sm text-zinc-300 font-medium mb-1 block">
                        Fecha de atención <span class="text-red-500">*</span>
                    </label>
                    <input type="date" id="fecha" name="fecha"
                           class="w-full px-4 py-3 bg-zinc-800 border border-zinc-700 rounded-lg text-white"
                           required>
                </div>

                {{-- Diagnóstico --}}
                <div>
                    <label for="diagnostico" class="text-sm text-zinc-300 font-medium mb-1 block">
                        Diagnóstico <span class="text-red-500">*</span>
                    </label>
                    <textarea id="diagnostico" name="diagnostico" rows="2"
                              class="w-full px-4 py-3 bg-zinc-800 border border-zinc-700 rounded-lg text-white"
                              required placeholder="Describa el diagnóstico..."></textarea>
                </div>

                {{-- Tratamiento --}}
                <div>
                    <label for="tratamiento" class="text-sm text-zinc-300 font-medium mb-1 block">
                        Tratamiento <span class="text-red-500">*</span>
                    </label>
                    <textarea id="tratamiento" name="tratamiento" rows="2"
                              class="w-full px-4 py-3 bg-zinc-800 border border-zinc-700 rounded-lg text-white"
                              required placeholder="Indique el tratamiento a seguir..."></textarea>
                </div>

                {{-- Observaciones --}}
                <div class="md:col-span-2">
                    <label for="observaciones" class="text-sm text-zinc-300 font-medium mb-1 block">
                        Observaciones
                    </label>
                    <textarea id="observaciones" name="observaciones" rows="3"
                              class="w-full px-4 py-3 bg-zinc-800 border border-zinc-700 rounded-lg text-white"
                              placeholder="Notas adicionales, evolución, recomendaciones, etc."></textarea>
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
                    Registrar Historia
                </button>
            </div>
        </form>
    </div>
</div>

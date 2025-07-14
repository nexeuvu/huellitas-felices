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
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold text-white">
                Registrar Nueva Raza
            </h1>
        </div>

        <form action="{{ route('admin.breed.store') }}" method="POST" class="space-y-6">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Especie -->
                <div>
                    <label for="species_id" class="text-sm text-zinc-300 font-medium mb-1 block">
                        Especie <span class="text-red-500">*</span>
                    </label>
                    <select id="species_id" name="species_id"
                        class="w-full px-4 py-3 bg-zinc-800 border border-zinc-700 rounded-lg text-white" required>
                        @foreach ($species as $specie)
                            <option value="{{ $specie->id }}">{{ $specie->nombre }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Nombre de la Raza -->
                <div>
                    <label for="nombre" class="text-sm text-zinc-300 font-medium mb-1 block">
                        Nombre de la Raza <span class="text-red-500">*</span>
                    </label>
                    <input type="text" id="nombre" name="nombre"
                        class="w-full px-4 py-3 bg-zinc-800 border border-zinc-700 rounded-lg text-white placeholder-zinc-500 focus:outline-none focus:ring-2 focus:ring-blue-500"
                        placeholder="Escribe el nombre de la raza"
                        required maxlength="255">
                </div>


                <!-- Características -->
                <div class="md:col-span-2">
                    <label for="caracteristicas" class="text-sm text-zinc-300 font-medium mb-1 block">
                        Características
                    </label>
                    <textarea id="caracteristicas" name="caracteristicas"
                        class="w-full px-4 py-3 bg-zinc-800 border border-zinc-700 rounded-lg text-white"
                        rows="4" maxlength="1000" placeholder="Descripción de la raza (opcional)"></textarea>
                </div>
            </div>

            <div class="text-sm text-zinc-500">
                Campos marcados con <span class="text-red-500 font-bold">*</span> son obligatorios
            </div>

            <div class="flex justify-end pt-4">
                <button type="submit"
                    class="px-6 py-3 bg-blue-600 text-white font-medium rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 focus:ring-offset-zinc-900 transition-all duration-200 shadow-lg hover:shadow-xl">
                    Registrar Raza
                </button>
            </div>
        </form>
    </div>
</div>

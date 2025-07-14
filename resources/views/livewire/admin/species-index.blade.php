<head>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<div class="w-full py-8 px-4 sm:px-6 lg:px-8">
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
            Registrar Nueva Especie
        </h1>

        <form action="{{ route('admin.species.store') }}" method="POST" class="space-y-6">
            @csrf

            <!-- Campo Tipo de Especie -->
            <div>
                <label for="nombre" class="block text-sm font-medium text-zinc-300 mb-1">
                    Tipo de especie <span class="text-red-500">*</span>
                </label>
                <select id="nombre" name="nombre"
                    class="w-full px-4 py-3 bg-zinc-800 border border-zinc-700 rounded-lg text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-200"
                    required onchange="mostrarDescripcion()">
                    <option value="" disabled selected>Seleccione una especie</option>
                    <option value="Canino">Canino</option>
                    <option value="Felino">Felino</option>
                    <option value="Ave">Ave</option>
                    <option value="Reptil">Reptil</option>
                    <option value="Roedor">Roedor</option>
                    <option value="Pez">Pez</option>
                </select>
                @error('nombre')
                    <p class="mt-1 text-sm text-red-500 font-medium">{{ $message }}</p>
                @enderror
            </div>

            <!-- Campo Descripción -->
            <div class="mt-4">
                <label for="descripcion" class="block text-sm font-medium text-zinc-300 mb-1">
                    Descripción
                </label>
                <textarea id="descripcion" name="descripcion" rows="3"
                    class="w-full px-4 py-3 bg-zinc-800 border border-zinc-700 rounded-lg text-white placeholder-zinc-500 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-200"
                    placeholder="Describe la especie (opcional)"></textarea>
                @error('descripcion')
                    <p class="mt-1 text-sm text-red-500 font-medium">{{ $message }}</p>
                @enderror
            </div>

            <!-- Separador -->
            <div class="relative my-8">
                <div class="absolute inset-0 flex items-center">
                    <div class="w-full border-t border-zinc-800"></div>
                </div>
            </div>

            <!-- Nota de campos obligatorios -->
            <div class="text-sm text-zinc-500 mb-6">
                Campos marcados con <span class="text-red-500 font-bold">*</span> son obligatorios
            </div>

            <!-- Botón de acción -->
            <div class="flex justify-end">
                <button type="submit"
                    class="px-6 py-3 bg-blue-600 text-white font-medium rounded-lg hover:bg-blue-700 focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 focus:ring-offset-zinc-900 transition-all duration-200 shadow-lg hover:shadow-xl">
                    Registrar Especie
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Script para descripción automática -->
<script>
    function mostrarDescripcion() {
        const especie = document.getElementById('nombre').value;
        const descripcion = document.getElementById('descripcion');

        const descripciones = {
            Canino: 'Mamíferos carnívoros como perros y lobos. Leales, inteligentes y domesticables.',
            Felino: 'Mamíferos ágiles y sigilosos como gatos, tigres y leones. Cazadores natos.',
            Ave: 'Animales vertebrados con plumas, la mayoría capaces de volar. Incluyen loros, águilas, etc.',
            Reptil: 'Animales de sangre fría como serpientes, lagartos y cocodrilos. Piel escamosa y hábitat variado.',
            Roedor: 'Pequeños mamíferos como ratones, hámsters y ardillas. Dientes incisivos en constante crecimiento.',
            Pez: 'Animales acuáticos con branquias. Incluyen especies como truchas, tiburones y peces de colores.'
        };

        descripcion.value = descripciones[especie] || '';
    }
</script>
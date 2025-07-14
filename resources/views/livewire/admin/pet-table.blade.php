<head>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://unpkg.com/alpinejs" defer></script>
</head>

<div class="w-full py-8 px-4 sm:px-6 lg:px-8" x-data="petTable()">
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
            <h1 class="text-2xl font-bold text-white">Lista de Mascotas</h1>
            <div class="space-x-2">
                <a href="{{ route('admin.pet.export-pdf') }}"
                    class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                    Exportar PDF
                </a>
                <a href="{{ route('admin.pet.export-excel') }}"
                    class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700">
                    Exportar Excel
                </a>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-zinc-800">
    <tr>
        <th class="px-4 py-3 text-sm text-zinc-300">#</th>
        <th class="px-4 py-3 text-sm text-zinc-300">Nombre</th>
        <th class="px-4 py-3 text-sm text-zinc-300">Foto</th> <!-- Nueva columna -->
        <th class="px-4 py-3 text-sm text-zinc-300">Propietario</th>
        <th class="px-4 py-3 text-sm text-zinc-300">Raza</th>
        <th class="px-4 py-3 text-sm text-zinc-300">Nacimiento</th>
        <th class="px-4 py-3 text-sm text-zinc-300">Género</th>
        <th class="px-4 py-3 text-sm text-zinc-300">Color</th>
        <th class="px-4 py-3 text-sm text-zinc-300">Peso</th>
        <th class="px-4 py-3 text-sm text-zinc-300">Acciones</th>
    </tr>
</thead>
            <tbody class="divide-y divide-zinc-800">
                @foreach ($pets as $pet)
                    <tr>
                        <td class="px-4 py-3 text-sm text-zinc-300">{{ $loop->iteration }}</td>
                        <td class="px-4 py-3 text-sm text-zinc-300">{{ $pet->nombre }}</td>
                        <td class="px-4 py-3 text-sm text-zinc-300">
                            @if ($pet->foto)
                                @php
                                    $esUrl = Str::startsWith($pet->foto, ['http://', 'https://']);
                                    $src = $esUrl ? $pet->foto : asset('storage/' . $pet->foto);
                                @endphp
                                <img src="{{ $src }}"
                                    alt="Foto de {{ $pet->nombre }}"
                                    class="w-14 h-14 object-cover rounded-full border border-zinc-700">
                            @else
                                <span class="text-zinc-500">Sin foto</span>
                            @endif
                        </td>

                        <td class="px-4 py-3 text-sm text-zinc-300">{{ $pet->customer->nombres }} {{ $pet->customer->apellidos }}</td>
                        <td class="px-4 py-3 text-sm text-zinc-300">{{ $pet->breed->nombre }}</td>
                        <td class="px-4 py-3 text-sm text-zinc-300">{{ optional($pet->fecha_nacimiento)->format('d/m/Y') }}</td>
                        <td class="px-4 py-3 text-sm text-zinc-300">{{ $pet->genero }}</td>
                        <td class="px-4 py-3 text-sm text-zinc-300">{{ $pet->color }}</td>
                        <td class="px-4 py-3 text-sm text-zinc-300">{{ $pet->peso }} kg</td>
                        <td class="px-4 py-3 text-sm text-right">
                            <button @click="openModal({{ $pet->id }}, '{{ addslashes($pet->nombre) }}', '{{ $pet->fecha_nacimiento }}', '{{ $pet->genero }}', '{{ addslashes($pet->color) }}', '{{ $pet->peso }}')"
                                    class="text-blue-500 hover:text-blue-400 mr-3">✏️</button>
                            <button onclick="confirmDelete({{ $pet->id }})" class="text-red-500 hover:text-red-400">🗑️</button>
                            <form id="delete-form-{{ $pet->id }}" action="{{ route('admin.pet.destroy', $pet->id) }}" method="POST" class="hidden">
                                @csrf
                                @method('DELETE')
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>

            </table>
        </div>

        @if ($pets->hasPages())
            <div class="mt-6">{{ $pets->links() }}</div>
        @endif
    </div>

    {{-- Modal --}}
    <template x-teleport="body">
        <div x-show="isOpen" x-cloak class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-75">
            <div class="bg-zinc-900 p-8 rounded-lg border border-zinc-700 w-full max-w-2xl">
                <form :action="'/admin/mascotas/' + currentId" method="POST">
                    @csrf
                    @method('PUT')
                    <h3 class="text-white text-xl font-semibold mb-6">Editar Mascota</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm text-zinc-300 mb-1">Nombre</label>
                            <input type="text" x-model="currentNombre" name="nombre"
                                   class="w-full px-4 py-2 bg-zinc-800 border border-zinc-700 rounded text-white" required>
                        </div>
                        <div>
                            <label class="block text-sm text-zinc-300 mb-1">Fecha de Nacimiento</label>
                            <input type="date" x-model="currentFechaNacimiento" name="fecha_nacimiento"
                                   class="w-full px-4 py-2 bg-zinc-800 border border-zinc-700 rounded text-white">
                        </div>
                        <div>
                            <label class="block text-sm text-zinc-300 mb-1">Género</label>
                            <select x-model="currentGenero" name="genero"
                                    class="w-full px-4 py-2 bg-zinc-800 border border-zinc-700 rounded text-white" required>
                                <option value="Macho">Macho</option>
                                <option value="Hembra">Hembra</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm text-zinc-300 mb-1">Color</label>
                            <input type="text" x-model="currentColor" name="color"
                                   class="w-full px-4 py-2 bg-zinc-800 border border-zinc-700 rounded text-white">
                        </div>
                        <div>
                            <label class="block text-sm text-zinc-300 mb-1">Peso (kg)</label>
                            <input type="number" step="0.01" x-model="currentPeso" name="peso"
                                   class="w-full px-4 py-2 bg-zinc-800 border border-zinc-700 rounded text-white">
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
            title: '¿Eliminar mascota?',
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

    function petTable() {
        return {
            isOpen: false,
            currentId: null,
            currentNombre: '',
            currentFechaNacimiento: '',
            currentGenero: '',
            currentColor: '',
            currentPeso: '',

            openModal(id, nombre, fecha_nacimiento, genero, color, peso) {
                this.currentId = id;
                this.currentNombre = nombre;
                this.currentFechaNacimiento = fecha_nacimiento;
                this.currentGenero = genero;
                this.currentColor = color;
                this.currentPeso = peso;
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

<div class="w-full py-8 px-4 sm:px-6 lg:px-8" x-data="breedEditor()">
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
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold text-white">Lista de Razas</h1>
            <div class="space-x-2">
                <a href="{{ route('admin.breed.export-pdf') }}" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">Exportar PDF</a>
                <a href="{{ route('admin.breed.export-excel') }}" class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700">Exportar Excel</a>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-zinc-800">
                    <tr>
                        <th class="px-4 py-3 text-left text-sm font-medium text-zinc-300 uppercase">#</th>
                        <th class="px-4 py-3 text-left text-sm font-medium text-zinc-300 uppercase">Raza</th>
                        <th class="px-4 py-3 text-left text-sm font-medium text-zinc-300 uppercase">Especie</th>
                        <th class="px-4 py-3 text-left text-sm font-medium text-zinc-300 uppercase">Características</th>
                        <th class="px-4 py-3 text-right text-sm font-medium text-zinc-300 uppercase">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-zinc-800">
                    @foreach ($breeds as $breed)
                        <tr>
                            <td class="px-4 py-4 text-sm text-zinc-300">{{ $loop->iteration }}</td>
                            <td class="px-4 py-4 text-sm text-zinc-300">{{ $breed->nombre }}</td>
                            <td class="px-4 py-4 text-sm text-zinc-300">{{ $breed->species->nombre ?? '—' }}</td>
                            <td class="px-4 py-4 text-sm text-zinc-300">{{ Str::limit($breed->caracteristicas, 60) }}</td>
                            <td class="px-4 py-4 text-sm text-right">
                                <button @click="openEditModal({{ $breed->id }}, '{{ addslashes($breed->nombre) }}', '{{ addslashes($breed->caracteristicas) }}', {{ $breed->species_id }})"
                                    class="text-blue-500 hover:text-blue-400 mr-3">
                                    ✏️
                                </button>
                                <button onclick="confirmDelete({{ $breed->id }})"
                                    class="text-red-500 hover:text-red-400">
                                    🗑️
                                </button>
                                <form id="delete-form-{{ $breed->id }}" action="{{ route('admin.breed.destroy', $breed->id) }}"
                                    method="POST" class="hidden">
                                    @csrf @method('DELETE')
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        @if ($breeds->hasPages())
            <div class="mt-6">{{ $breeds->links() }}</div>
        @endif
    </div>

    {{-- Modal de Edición --}}
    <template x-teleport="body">
        <div x-show="showEdit" x-cloak class="fixed inset-0 bg-black bg-opacity-60 z-50 flex items-center justify-center">
            <div class="bg-zinc-900 rounded-xl shadow-2xl border border-zinc-700 w-full max-w-2xl p-6">
                <h2 class="text-xl font-bold text-white mb-4">Editar Raza</h2>
                <form :action="`/admin/raza/${editId}`" method="POST">
                    @csrf @method('PUT')
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="text-zinc-300 text-sm">Nombre</label>
                            <input type="text" name="nombre" x-model="editNombre" required
                                class="w-full bg-zinc-800 text-white px-3 py-2 rounded-lg border border-zinc-600" />
                        </div>
                        <div>
                            <label class="text-zinc-300 text-sm">Especie</label>
                            <select name="species_id" x-model="editSpeciesId"
                                class="w-full bg-zinc-800 text-white px-3 py-2 rounded-lg border border-zinc-600" required>
                                <option value="" disabled>Seleccione especie</option>
                                @foreach ($species as $specie)
                                    <option value="{{ $specie->id }}">{{ $specie->nombre }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="mt-4">
                        <label class="text-zinc-300 text-sm">Características</label>
                        <textarea name="caracteristicas" x-model="editCaracteristicas" rows="3"
                            class="w-full bg-zinc-800 text-white px-3 py-2 rounded-lg border border-zinc-600"></textarea>
                    </div>
                    <div class="mt-6 flex justify-end gap-4">
                        <button type="button" @click="showEdit = false"
                            class="bg-zinc-700 hover:bg-zinc-600 text-white px-4 py-2 rounded-lg">Cancelar</button>
                        <button type="submit"
                            class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg">Actualizar</button>
                    </div>
                </form>
            </div>
        </div>
    </template>
</div>

<script>
    function breedEditor() {
        return {
            showEdit: false,
            editId: null,
            editNombre: '',
            editCaracteristicas: '',
            editSpeciesId: '',
            openEditModal(id, nombre, caracteristicas, speciesId) {
                this.editId = id;
                this.editNombre = nombre;
                this.editCaracteristicas = caracteristicas;
                this.editSpeciesId = speciesId;
                this.showEdit = true;
            }
        };
    }

    function confirmDelete(id) {
        Swal.fire({
            title: '¿Eliminar raza?',
            text: "Esta acción no se puede deshacer.",
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
</script>

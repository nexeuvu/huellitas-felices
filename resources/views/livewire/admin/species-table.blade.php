<div class="w-full py-8 px-4 sm:px-6 lg:px-8" x-data="speciesTable()">
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

    {{-- Tabla de Especies --}}
    <div class="w-full bg-zinc-900 rounded-xl shadow-2xl overflow-hidden p-6 border border-zinc-800">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold text-white">Lista de Especies</h1>
            <div class="space-x-2">
                <a href="{{ route('admin.species.export-pdf') }}"
                    class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                    Exportar PDF
                </a>
                <a href="{{ route('admin.species.export-excel') }}"
                    class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700">
                    Exportar Excel
                </a>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-zinc-800">
                    <tr>
                        <th class="px-4 py-3 text-left text-sm font-medium text-zinc-300 uppercase">#</th>
                        <th class="px-4 py-3 text-left text-sm font-medium text-zinc-300 uppercase">Nombre</th>
                        <th class="px-4 py-3 text-left text-sm font-medium text-zinc-300 uppercase">Descripción</th>
                        <th class="px-4 py-3 text-right text-sm font-medium text-zinc-300 uppercase">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-zinc-800">
                    @foreach ($species as $specie)
                        <tr>
                            <td class="px-4 py-4 text-sm text-zinc-300">{{ $loop->iteration }}</td>
                            <td class="px-4 py-4 text-sm text-zinc-300">{{ $specie->nombre }}</td>
                            <td class="px-4 py-4 text-sm text-zinc-300">{{ Str::limit($specie->descripcion, 50) }}</td>
                            <td class="px-4 py-4 text-sm text-right">
                                {{-- Botón Editar --}}
                                <button
                                    @click="openModal({{ $specie->id }}, '{{ addslashes($specie->nombre) }}', '{{ addslashes($specie->descripcion) }}')"
                                    class="text-blue-500 hover:text-blue-400 mr-3">
                                    <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M13.586 3.586a2 2 0 112.828 2.828L11.379 11.45 3 17h2.828l8.379-8.379-2.621-2.621z" />
                                    </svg>
                                </button>

                                {{-- Botón Eliminar --}}
                                <button onclick="confirmDelete({{ $specie->id }})"
                                    class="text-red-500 hover:text-red-400">
                                    <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd"
                                            d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z"
                                            clip-rule="evenodd" />
                                    </svg>
                                </button>

                                <form id="delete-form-{{ $specie->id }}"
                                    action="{{ route('admin.species.destroy', $specie->id) }}" method="POST"
                                    class="hidden">
                                    @csrf
                                    @method('DELETE')
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{-- Paginación --}}
        @if ($species->hasPages())
            <div class="mt-6">
                {{ $species->links() }}
            </div>
        @endif
    </div>

    {{-- Modal de edición --}}
    <template x-teleport="body">
        <div x-show="isOpen" x-cloak class="fixed inset-0 z-50 overflow-y-auto" x-transition>
            <div class="flex items-center justify-center min-h-screen px-4 py-8">
                <div class="fixed inset-0 bg-black bg-opacity-75" @click="closeModal"></div>
                <div class="relative bg-zinc-900 border border-zinc-700 rounded-lg shadow-xl w-full max-w-2xl p-8">
                    <form :action="'/admin/species/' + currentId" method="POST">
                        @csrf
                        @method('PUT')

                        <h2 class="text-white text-xl font-semibold mb-6">Editar Especie</h2>

                        <div class="mb-4">
                            <label class="block text-sm text-zinc-300 mb-2">Nombre</label>
                            <input type="text" x-model="currentName" name="nombre"
                                class="w-full px-4 py-2 bg-zinc-800 border border-zinc-600 rounded-lg text-white"
                                required>
                        </div>

                        <div class="mb-6">
                            <label class="block text-sm text-zinc-300 mb-2">Descripción</label>
                            <textarea x-model="currentDescription" name="descripcion" rows="4"
                                class="w-full px-4 py-2 bg-zinc-800 border border-zinc-600 rounded-lg text-white"
                                required></textarea>
                        </div>

                        <div class="flex justify-end space-x-4">
                            <button type="button" @click="closeModal"
                                class="px-4 py-2 text-zinc-300 hover:text-white">Cancelar</button>
                            <button type="submit"
                                class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">Guardar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </template>
</div>

<script>
    function confirmDelete(id) {
        Swal.fire({
            title: '¿Eliminar especie?',
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
            customClass: {
                popup: 'rounded-lg shadow-lg'
            }
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('delete-form-' + id).submit();
            }
        });
    }

    function speciesTable() {
        return {
            isOpen: false,
            currentId: null,
            currentName: '',
            currentDescription: '',

            openModal(id, name, description) {
                this.currentId = id;
                this.currentName = name;
                this.currentDescription = description;
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

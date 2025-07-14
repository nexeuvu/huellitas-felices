<head>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://unpkg.com/alpinejs" defer></script>
</head>

<div class="w-full py-8 px-4 sm:px-6 lg:px-8" x-data="veterinaryTable()">
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
            <h1 class="text-2xl font-bold text-white">Lista de Veterinarios</h1>
            <div class="space-x-2">
                <a href="{{ route('admin.veterinary.export-pdf') }}"
                    class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                    Exportar PDF
                </a>
                <a href="{{ route('admin.veterinary.export-excel') }}"
                    class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700">
                    Exportar Excel
                </a>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-zinc-800">
                    <tr>
                        <th class="px-4 py-3 text-left text-sm text-zinc-300 uppercase">#</th>
                        <th class="px-4 py-3 text-left text-sm text-zinc-300 uppercase">Empleado</th>
                        <th class="px-4 py-3 text-left text-sm text-zinc-300 uppercase">Especialidad</th>
                        <th class="px-4 py-3 text-left text-sm text-zinc-300 uppercase">Licencia</th>
                        <th class="px-4 py-3 text-right text-sm text-zinc-300 uppercase">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-zinc-800">
                    @foreach ($veterinaries as $veterinary)
                        <tr>
                            <td class="px-4 py-4 text-sm text-zinc-300">{{ $loop->iteration }}</td>
                            <td class="px-4 py-4 text-sm text-zinc-300">
                                {{ $veterinary->employee->nombres }} {{ $veterinary->employee->apellidos }} - {{ $veterinary->employee->dni }}
                            </td>
                            <td class="px-4 py-4 text-sm text-zinc-300">{{ $veterinary->especialidad }}</td>
                            <td class="px-4 py-4 text-sm text-zinc-300">{{ $veterinary->licencia }}</td>
                            <td class="px-4 py-4 text-sm text-right">
                                <button
                                    @click="openModal({{ $veterinary->id }}, '{{ addslashes($veterinary->especialidad) }}', '{{ addslashes($veterinary->licencia) }}')"
                                    class="text-blue-500 hover:text-blue-400 mr-3">✏️</button>
                                <button onclick="confirmDelete({{ $veterinary->id }})"
                                        class="text-red-500 hover:text-red-400">🗑️</button>
                                <form id="delete-form-{{ $veterinary->id }}"
                                      action="{{ route('admin.veterinary.destroy', $veterinary->id) }}"
                                      method="POST" class="hidden">
                                    @csrf
                                    @method('DELETE')
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        @if ($veterinaries->hasPages())
            <div class="mt-6">{{ $veterinaries->links() }}</div>
        @endif
    </div>

    {{-- Modal de edición --}}
    <template x-teleport="body">
        <div x-show="isOpen" x-cloak class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-75">
            <div class="bg-zinc-900 p-8 rounded-lg border border-zinc-700 w-full max-w-xl">
                <form :action="'/admin/veterinary/' + currentId" method="POST">
                    @csrf
                    @method('PUT')
                    <h3 class="text-white text-xl font-semibold mb-6">Editar Veterinario</h3>
                    <div class="grid grid-cols-1 gap-6">
                        <div>
                            <label class="block text-sm text-zinc-300 mb-1">Especialidad</label>
                            <input type="text" x-model="currentEspecialidad" name="especialidad"
                                   class="w-full px-4 py-2 bg-zinc-800 border border-zinc-700 rounded text-white"
                                   maxlength="100" required>
                        </div>
                        <div>
                            <label class="block text-sm text-zinc-300 mb-1">Licencia</label>
                            <input type="text" x-model="currentLicencia" name="licencia"
                                   class="w-full px-4 py-2 bg-zinc-800 border border-zinc-700 rounded text-white"
                                   maxlength="50" required>
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
            title: '¿Eliminar veterinario?',
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

    function veterinaryTable() {
        return {
            isOpen: false,
            currentId: null,
            currentEspecialidad: '',
            currentLicencia: '',

            openModal(id, especialidad, licencia) {
                this.currentId = id;
                this.currentEspecialidad = especialidad;
                this.currentLicencia = licencia;
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

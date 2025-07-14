<div class="w-full py-8 px-4 sm:px-6 lg:px-8" x-data="serviceEditor()">
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
            <h1 class="text-2xl font-bold text-white">Lista de Servicios</h1>
            <div class="space-x-2">
                <a href="{{ route('admin.service.export-pdf') }}"
                    class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                    Exportar PDF
                </a>
                <a href="{{ route('admin.service.export-excel') }}"
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
                        <th class="px-4 py-3 text-left text-sm font-medium text-zinc-300 uppercase">Duración (min)</th>
                        <th class="px-4 py-3 text-left text-sm font-medium text-zinc-300 uppercase">Costo (S/.)</th>
                        <th class="px-4 py-3 text-right text-sm font-medium text-zinc-300 uppercase">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-zinc-800">
                    @foreach ($services as $service)
                        <tr>
                            <td class="px-4 py-4 text-sm text-zinc-300">{{ $loop->iteration }}</td>
                            <td class="px-4 py-4 text-sm text-zinc-300">{{ $service->nombre }}</td>
                            <td class="px-4 py-4 text-sm text-zinc-300">{{ Str::limit($service->descripcion, 50) }}</td>
                            <td class="px-4 py-4 text-sm text-zinc-300">{{ $service->duracion_min }}</td>
                            <td class="px-4 py-4 text-sm text-zinc-300">S/. {{ number_format($service->costo, 2) }}</td>
                            <td class="px-4 py-4 text-sm text-right">
                                <button @click="openEditModal({{ $service->id }}, '{{ addslashes($service->nombre) }}', '{{ addslashes($service->descripcion) }}', {{ $service->duracion_min }}, {{ $service->costo }})"
                                    class="text-blue-500 hover:text-blue-400 mr-3">
                                    <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z" />
                                    </svg>
                                </button>
                                <button onclick="confirmDelete({{ $service->id }})" class="text-red-500 hover:text-red-400">
                                    <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" />
                                    </svg>
                                </button>
                                <form id="delete-form-{{ $service->id }}" action="{{ route('admin.service.destroy', $service->id) }}" method="POST" class="hidden">
                                    @csrf
                                    @method('DELETE')
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        @if ($services->hasPages())
            <div class="mt-6">{{ $services->links() }}</div>
        @endif
    </div>

    {{-- Modal de Edición --}}
    <template x-teleport="body">
        <div x-show="showEdit" x-cloak class="fixed inset-0 bg-black bg-opacity-60 z-50 flex items-center justify-center">
            <div class="bg-zinc-900 rounded-xl shadow-2xl border border-zinc-700 w-full max-w-2xl p-6">
                <h2 class="text-xl font-bold text-white mb-4">Editar Servicio</h2>
                <form :action="`/admin/servicio/${editId}`" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="text-zinc-300 text-sm">Nombre</label>
                            <input type="text" name="nombre" x-model="editNombre" required
                                class="w-full bg-zinc-800 text-white px-3 py-2 rounded-lg border border-zinc-600" />
                        </div>
                        <div>
                            <label class="text-zinc-300 text-sm">Duración (min)</label>
                            <input type="number" name="duracion_min" x-model="editDuracionMin" min="0" required
                                class="w-full bg-zinc-800 text-white px-3 py-2 rounded-lg border border-zinc-600" />
                        </div>
                        <div>
                            <label class="text-zinc-300 text-sm">Costo (S/.)</label>
                            <input type="number" name="costo" x-model="editCosto" step="0.01" min="0" required
                                class="w-full bg-zinc-800 text-white px-3 py-2 rounded-lg border border-zinc-600" />
                        </div>
                    </div>
                    <div class="mt-4">
                        <label class="text-zinc-300 text-sm">Descripción</label>
                        <textarea name="descripcion" x-model="editDescripcion" rows="3"
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
    function serviceEditor() {
        return {
            showEdit: false,
            editId: null,
            editNombre: '',
            editDescripcion: '',
            editDuracionMin: 0,
            editCosto: 0,
            openEditModal(id, nombre, descripcion, duracion_min, costo) {
                this.editId = id;
                this.editNombre = nombre;
                this.editDescripcion = descripcion;
                this.editDuracionMin = duracion_min;
                this.editCosto = costo;
                this.showEdit = true;
            }
        };
    }

    function confirmDelete(id) {
        Swal.fire({
            title: '¿Eliminar servicio?',
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

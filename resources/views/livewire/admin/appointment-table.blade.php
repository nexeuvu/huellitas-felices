<head>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://unpkg.com/alpinejs" defer></script>
</head>

<div class="w-full py-8 px-4 sm:px-6 lg:px-8" x-data="appointmentTable()">
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
            <h1 class="text-2xl font-bold text-white">Lista de Citas</h1>
            <div class="space-x-2">
                <a href="{{ route('admin.appointment.export-pdf') }}" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">Exportar PDF</a>
                <a href="{{ route('admin.appointment.export-excel') }}" class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700">Exportar Excel</a>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-zinc-800">
                    <tr>
                        <th class="px-4 py-3 text-sm text-zinc-300">#</th>
                        <th class="px-4 py-3 text-sm text-zinc-300">Mascota</th>
                        <th class="px-4 py-3 text-sm text-zinc-300">Veterinario</th>
                        <th class="px-4 py-3 text-sm text-zinc-300">Servicio</th>
                        <th class="px-4 py-3 text-sm text-zinc-300">Fecha y Hora</th>
                        <th class="px-4 py-3 text-sm text-zinc-300">Estado</th>
                        <th class="px-4 py-3 text-sm text-zinc-300">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-zinc-800">
                    @foreach ($appointments as $appointment)
                        <tr>
                            <td class="px-4 py-3 text-sm text-zinc-300">{{ $loop->iteration }}</td>
                            <td class="px-4 py-3 text-sm text-zinc-300">{{ $appointment->pet->nombre }}</td>
                            <td class="px-4 py-3 text-sm text-zinc-300">{{ $appointment->veterinary->id }}</td>
                            <td class="px-4 py-3 text-sm text-zinc-300">{{ $appointment->service->nombre }}</td>
                            <td class="px-4 py-3 text-sm text-zinc-300">{{ $appointment->fecha_hora->format('d/m/Y H:i') }}</td>
                            <td class="px-4 py-3 text-sm text-zinc-300">{{ ucfirst($appointment->estado) }}</td>
                            <td class="px-4 py-3 text-sm text-right">
                                <button @click="openModal({{ $appointment->id }}, {{ $appointment->pet_id }}, {{ $appointment->veterinary_id }}, {{ $appointment->service_id }}, '{{ $appointment->fecha_hora->format('Y-m-d\TH:i') }}', '{{ $appointment->estado }}', '{{ addslashes($appointment->notas) }}')"
                                        class="text-blue-500 hover:text-blue-400 mr-3">✏️</button>
                                <button onclick="confirmDelete({{ $appointment->id }})" class="text-red-500 hover:text-red-400">🗑️</button>
                                <form id="delete-form-{{ $appointment->id }}" action="{{ route('admin.appointment.destroy', $appointment->id) }}" method="POST" class="hidden">
                                    @csrf
                                    @method('DELETE')
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        @if ($appointments->hasPages())
            <div class="mt-6">{{ $appointments->links() }}</div>
        @endif
    </div>

    {{-- Modal Edición --}}
    <template x-teleport="body">
        <div x-show="isOpen" x-cloak class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-75">
            <div class="bg-zinc-900 p-8 rounded-lg border border-zinc-700 w-full max-w-2xl">
                <form :action="'/admin/appointments/' + currentId" method="POST">
                    @csrf
                    @method('PUT')
                    <h3 class="text-white text-xl font-semibold mb-6">Editar Cita</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm text-zinc-300 mb-1">Fecha y Hora</label>
                            <input type="datetime-local" x-model="currentFechaHora" name="fecha_hora"
                                   class="w-full px-4 py-2 bg-zinc-800 border border-zinc-700 rounded text-white" required>
                        </div>
                        <div>
                            <label class="block text-sm text-zinc-300 mb-1">Estado</label>
                            <select x-model="currentEstado" name="estado"
                                    class="w-full px-4 py-2 bg-zinc-800 border border-zinc-700 rounded text-white" required>
                                <option value="pendiente">Pendiente</option>
                                <option value="confirmado">Confirmado</option>
                                <option value="completado">Completado</option>
                                <option value="cancelado">Cancelado</option>
                            </select>
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-sm text-zinc-300 mb-1">Notas</label>
                            <textarea x-model="currentNotas" name="notas" rows="3"
                                      class="w-full px-4 py-2 bg-zinc-800 border border-zinc-700 rounded text-white"
                                      placeholder="Notas adicionales..."></textarea>
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
            title: '¿Eliminar cita?',
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

    function appointmentTable() {
        return {
            isOpen: false,
            currentId: null,
            currentPetId: null,
            currentVeterinaryId: null,
            currentServiceId: null,
            currentFechaHora: '',
            currentEstado: 'pendiente',
            currentNotas: '',

            openModal(id, petId, veterinaryId, serviceId, fechaHora, estado, notas) {
                this.currentId = id;
                this.currentPetId = petId;
                this.currentVeterinaryId = veterinaryId;
                this.currentServiceId = serviceId;
                this.currentFechaHora = fechaHora;
                this.currentEstado = estado;
                this.currentNotas = notas;
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

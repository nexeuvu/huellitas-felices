<head>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://unpkg.com/alpinejs" defer></script>
</head>

<div class="w-full py-8 px-4 sm:px-6 lg:px-8" x-data="employeeTable()">
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
            <h1 class="text-2xl font-bold text-white">Lista de Empleados</h1>
            <div class="space-x-2">
                <a href="{{ route('admin.employee.export-pdf') }}"
                   class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">Exportar PDF</a>
                <a href="{{ route('admin.employee.export-excel') }}"
                   class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700">Exportar Excel</a>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-zinc-800">
                    <tr>
                        <th class="px-4 py-3 text-left text-sm text-zinc-300 uppercase">#</th>
                        <th class="px-4 py-3 text-left text-sm text-zinc-300 uppercase">Documento</th>
                        <th class="px-4 py-3 text-left text-sm text-zinc-300 uppercase">DNI</th>
                        <th class="px-4 py-3 text-left text-sm text-zinc-300 uppercase">Nombres</th>
                        <th class="px-4 py-3 text-left text-sm text-zinc-300 uppercase">Apellidos</th>
                        <th class="px-4 py-3 text-left text-sm text-zinc-300 uppercase">Teléfono</th>
                        <th class="px-4 py-3 text-left text-sm text-zinc-300 uppercase">Email</th>
                        <th class="px-4 py-3 text-left text-sm text-zinc-300 uppercase">Puesto</th>
                        <th class="px-4 py-3 text-left text-sm text-zinc-300 uppercase">Contratación</th>
                        <th class="px-4 py-3 text-right text-sm text-zinc-300 uppercase">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-zinc-800">
                    @foreach ($employees as $employee)
                        <tr>
                            <td class="px-4 py-4 text-sm text-zinc-300">{{ $loop->iteration }}</td>
                            <td class="px-4 py-4 text-sm text-zinc-300">{{ $employee->tipo_documento }}</td>
                            <td class="px-4 py-4 text-sm text-zinc-300">{{ $employee->dni }}</td>
                            <td class="px-4 py-4 text-sm text-zinc-300">{{ $employee->nombres }}</td>
                            <td class="px-4 py-4 text-sm text-zinc-300">{{ $employee->apellidos }}</td>
                            <td class="px-4 py-4 text-sm text-zinc-300">{{ $employee->telefono }}</td>
                            <td class="px-4 py-4 text-sm text-zinc-300">{{ $employee->email ?? 'N/A' }}</td>
                            <td class="px-4 py-4 text-sm text-zinc-300">{{ $employee->puesto }}</td>
                            <td class="px-4 py-4 text-sm text-zinc-300">{{ \Carbon\Carbon::parse($employee->fecha_contratacion)->format('d/m/Y') }}</td>
                            <td class="px-4 py-4 text-sm text-right">
                                <button
                                    @click="openModal(
                                        {{ $employee->id }},
                                        '{{ addslashes($employee->tipo_documento) }}',
                                        '{{ addslashes($employee->dni) }}',
                                        '{{ addslashes($employee->nombres) }}',
                                        '{{ addslashes($employee->apellidos) }}',
                                        '{{ addslashes($employee->telefono) }}',
                                        '{{ addslashes($employee->email) }}',
                                        '{{ addslashes($employee->puesto) }}',
                                        '{{ $employee->fecha_contratacion }}'
                                    )"
                                    class="text-blue-500 hover:text-blue-400 mr-3">
                                    ✏️
                                </button>
                                <button onclick="confirmDelete({{ $employee->id }})"
                                        class="text-red-500 hover:text-red-400">🗑️</button>
                                <form id="delete-form-{{ $employee->id }}"
                                      action="{{ route('admin.employee.destroy', $employee->id) }}"
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

        @if ($employees->hasPages())
            <div class="mt-6">{{ $employees->links() }}</div>
        @endif
    </div>

    {{-- Modal --}}
    <template x-teleport="body">
        <div x-show="isOpen" x-cloak class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-75">
            <div class="bg-zinc-900 p-8 rounded-lg border border-zinc-700 w-full max-w-3xl">
                <form :action="'/admin/employee/' + currentId" method="POST">
                    @csrf
                    @method('PUT')
                    <h3 class="text-white text-xl font-semibold mb-6">Editar Empleado</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm text-zinc-300 mb-1">Tipo de Documento</label>
                            <select x-model="currentTipoDocumento" name="tipo_documento"
                                class="w-full px-4 py-2 bg-zinc-800 border border-zinc-700 rounded text-white" required>
                                <option value="DNI">DNI</option>
                                <option value="CE">Carné de Extranjería</option>
                                <option value="PASAPORTE">Pasaporte</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm text-zinc-300 mb-1">DNI</label>
                            <input type="text" x-model="currentDni" name="dni"
                                class="w-full px-4 py-2 bg-zinc-800 border border-zinc-700 rounded text-white" maxlength="20" required>
                        </div>
                        <div>
                            <label class="block text-sm text-zinc-300 mb-1">Nombres</label>
                            <input type="text" x-model="currentNombres" name="nombres"
                                class="w-full px-4 py-2 bg-zinc-800 border border-zinc-700 rounded text-white" maxlength="100" required>
                        </div>
                        <div>
                            <label class="block text-sm text-zinc-300 mb-1">Apellidos</label>
                            <input type="text" x-model="currentApellidos" name="apellidos"
                                class="w-full px-4 py-2 bg-zinc-800 border border-zinc-700 rounded text-white" maxlength="100" required>
                        </div>
                        <div>
                            <label class="block text-sm text-zinc-300 mb-1">Teléfono</label>
                            <input type="text" x-model="currentTelefono" name="telefono"
                                class="w-full px-4 py-2 bg-zinc-800 border border-zinc-700 rounded text-white">
                        </div>
                        <div>
                            <label class="block text-sm text-zinc-300 mb-1">Email</label>
                            <input type="email" x-model="currentEmail" name="email"
                                class="w-full px-4 py-2 bg-zinc-800 border border-zinc-700 rounded text-white">
                        </div>
                        <div>
                            <label class="block text-sm text-zinc-300 mb-1">Puesto</label>
                            <input type="text" x-model="currentPuesto" name="puesto"
                                class="w-full px-4 py-2 bg-zinc-800 border border-zinc-700 rounded text-white">
                        </div>
                        <div>
                            <label class="block text-sm text-zinc-300 mb-1">Fecha de Contratación</label>
                            <input type="date" x-model="currentFechaContratacion" name="fecha_contratacion"
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
            title: '¿Eliminar empleado?',
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

    function employeeTable() {
        return {
            isOpen: false,
            currentId: null,
            currentTipoDocumento: '',
            currentDni: '',
            currentNombres: '',
            currentApellidos: '',
            currentTelefono: '',
            currentEmail: '',
            currentPuesto: '',
            currentFechaContratacion: '',

            openModal(id, tipo_documento, dni, nombres, apellidos, telefono, email, puesto, fecha_contratacion) {
                this.currentId = id;
                this.currentTipoDocumento = tipo_documento;
                this.currentDni = dni;
                this.currentNombres = nombres;
                this.currentApellidos = apellidos;
                this.currentTelefono = telefono;
                this.currentEmail = email;
                this.currentPuesto = puesto;
                this.currentFechaContratacion = fecha_contratacion;
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

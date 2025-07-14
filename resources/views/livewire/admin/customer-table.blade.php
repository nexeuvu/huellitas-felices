<!-- Encabezados -->
<head>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://unpkg.com/alpinejs" defer></script>
</head>

<div class="w-full py-8 px-4 sm:px-6 lg:px-8" x-data="customerTable()">
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
    @if ($errors->any()))
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
            <h1 class="text-2xl font-bold text-white">Lista de Clientes</h1>
            <div class="space-x-2">
                <a href="{{ route('admin.customer.export-pdf') }}" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">Exportar PDF</a>
                <a href="{{ route('admin.customer.export-excel') }}" class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700">Exportar Excel</a>
            </div>
        </div>

        <!-- Tabla -->
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-zinc-800">
                    <tr>
                        <th class="px-4 py-3 text-left text-sm text-zinc-300">#</th>
                        <th class="px-4 py-3 text-left text-sm text-zinc-300">Tipo Documento</th>
                        <th class="px-4 py-3 text-left text-sm text-zinc-300">Documento</th>
                        <th class="px-4 py-3 text-left text-sm text-zinc-300">Nombres</th>
                        <th class="px-4 py-3 text-left text-sm text-zinc-300">Apellidos</th>
                        <th class="px-4 py-3 text-left text-sm text-zinc-300">Teléfono</th>
                        <th class="px-4 py-3 text-left text-sm text-zinc-300">Email</th>
                        <th class="px-4 py-3 text-right text-sm text-zinc-300">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-zinc-800">
                    @foreach ($customers as $customer)
                        <tr>
                            <td class="px-4 py-4 text-sm text-zinc-300">{{ $loop->iteration }}</td>
                            <td class="px-4 py-4 text-sm text-zinc-300">{{ $customer->tipo_documento }}</td>
                            <td class="px-4 py-4 text-sm text-zinc-300">{{ $customer->dni }}</td>
                            <td class="px-4 py-4 text-sm text-zinc-300">{{ $customer->nombres }}</td>
                            <td class="px-4 py-4 text-sm text-zinc-300">{{ $customer->apellidos }}</td>
                            <td class="px-4 py-4 text-sm text-zinc-300">{{ $customer->telefono }}</td>
                            <td class="px-4 py-4 text-sm text-zinc-300">{{ $customer->email }}</td>
                            <td class="px-4 py-4 text-sm text-right">
                                <button @click="openModal({{ $customer->id }}, '{{ $customer->tipo_documento }}', '{{ $customer->dni }}', '{{ $customer->nombres }}', '{{ $customer->apellidos }}', '{{ $customer->direccion }}', '{{ $customer->telefono }}', '{{ $customer->email }}', '{{ $customer->fecha_registro }}')" 
                                    class="text-blue-500 hover:text-blue-400 mr-3">
                                    ✏️
                                </button>
                                <form id="delete-form-{{ $customer->id }}" action="{{ route('admin.customer.destroy', $customer->id) }}" method="POST" class="hidden">
                                    @csrf @method('DELETE')
                                </form>
                                <button onclick="confirmDelete({{ $customer->id }})" class="text-red-500 hover:text-red-400">🗑️</button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Paginación -->
        @if ($customers->hasPages())
            <div class="mt-6">{{ $customers->links() }}</div>
        @endif
    </div>

    <!-- Modal Editar -->
    <template x-teleport="body">
        <div x-show="isOpen" x-cloak class="fixed inset-0 z-50 overflow-y-auto">
            <div class="flex items-center justify-center min-h-screen px-4">
                <div class="fixed inset-0 bg-black opacity-75" @click="closeModal"></div>
                <div class="bg-zinc-900 border border-zinc-800 rounded-lg w-full max-w-4xl mx-auto mt-12 p-8 shadow-xl text-white">
                    <form :action="'/admin/customers/' + currentId" method="POST">
                        @csrf @method('PUT')
                        <h3 class="text-xl font-bold mb-6">Editar Cliente</h3>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm mb-1">Tipo de Documento</label>
                                <select x-model="currentDocumentType" name="tipo_documento" class="w-full px-4 py-3 bg-zinc-800 border border-zinc-700 rounded-lg text-white">
                                    <option value="DNI">DNI</option>
                                    <option value="RUC">RUC</option>
                                    <option value="CE">Carné de Extranjería</option>
                                    <option value="PASAPORTE">Pasaporte</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm mb-1">Número de Documento</label>
                                <input type="text" x-model="currentDni" name="dni" class="w-full px-4 py-3 bg-zinc-800 border border-zinc-700 rounded-lg text-white">
                            </div>
                            <div>
                                <label class="block text-sm mb-1">Nombres</label>
                                <input type="text" x-model="currentName" name="nombres" class="w-full px-4 py-3 bg-zinc-800 border border-zinc-700 rounded-lg text-white">
                            </div>
                            <div>
                                <label class="block text-sm mb-1">Apellidos</label>
                                <input type="text" x-model="currentLastName" name="apellidos" class="w-full px-4 py-3 bg-zinc-800 border border-zinc-700 rounded-lg text-white">
                            </div>
                            <div>
                                <label class="block text-sm mb-1">Dirección</label>
                                <input type="text" x-model="currentAddress" name="direccion" class="w-full px-4 py-3 bg-zinc-800 border border-zinc-700 rounded-lg text-white">
                            </div>
                            <div>
                                <label class="block text-sm mb-1">Teléfono</label>
                                <input type="text" x-model="currentPhone" name="telefono" class="w-full px-4 py-3 bg-zinc-800 border border-zinc-700 rounded-lg text-white">
                            </div>
                            <div>
                                <label class="block text-sm mb-1">Email</label>
                                <input type="email" x-model="currentEmail" name="email" class="w-full px-4 py-3 bg-zinc-800 border border-zinc-700 rounded-lg text-white">
                            </div>
                            <div>
                                <label class="block text-sm mb-1">Fecha de Registro</label>
                                <input type="date" x-model="currentRegistrationDate" name="fecha_registro" class="w-full px-4 py-3 bg-zinc-800 border border-zinc-700 rounded-lg text-white">
                            </div>
                        </div>

                        <div class="mt-6 flex justify-end gap-4">
                            <button type="button" @click="closeModal" class="text-zinc-300 hover:text-white">Cancelar</button>
                            <button type="submit" class="px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700">Guardar Cambios</button>
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
            title: '¿Eliminar cliente?',
            text: "No podrás revertir esta acción.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3b82f6',
            cancelButtonColor: '#6b7280',
            confirmButtonText: 'Sí, eliminar',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('delete-form-' + id).submit();
            }
        });
    }

    function customerTable() {
        return {
            isOpen: false,
            currentId: null,
            currentDocumentType: '',
            currentDni: '',
            currentName: '',
            currentLastName: '',
            currentAddress: '',
            currentPhone: '',
            currentEmail: '',
            currentRegistrationDate: '',

            openModal(id, document_type, dni, name, last_name, address, phone, email, fecha_registro) {
                this.currentId = id;
                this.currentDocumentType = document_type;
                this.currentDni = dni;
                this.currentName = name;
                this.currentLastName = last_name;
                this.currentAddress = address;
                this.currentPhone = phone;
                this.currentEmail = email;
                this.currentRegistrationDate = fecha_registro;
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
<head>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<div class="w-full py-8 px-4 sm:px-6 lg:px-8">
    {{-- Alerta de éxito --}}
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

    {{-- Errores de validación --}}
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
            Registrar Nuevo Producto
        </h1>

        <form action="{{ route('admin.product.store') }}" method="POST" class="space-y-6">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                {{-- Nombre del Producto --}}
                <div class="md:col-span-2">
                    <label for="nombre" class="block text-sm font-medium text-zinc-300 mb-1">
                        Nombre del Producto <span class="text-red-500">*</span>
                    </label>
                    <select id="nombre" name="nombre"
                        class="w-full px-4 py-3 bg-zinc-800 border border-zinc-700 rounded-lg text-white"
                        required>
                        <option value="" disabled selected>Seleccione un producto</option>
                        <option value="Shampoo Antipulgas">Shampoo Antipulgas</option>
                        <option value="Juguete para Perro">Juguete para Perro</option>
                        <option value="Antibiótico">Antibiótico</option>
                        <option value="Vitaminas">Vitaminas</option>
                        <option value="Arena para Gato">Arena para Gato</option>
                        <option value="Peine para Mascotas">Peine para Mascotas</option>
                        <option value="Collar Antipulgas">Collar Antipulgas</option>
                        <option value="Desparasitante">Desparasitante</option>
                        <option value="Galletas para Perros">Galletas para Perros</option>
                        <option value="Comedero Automático">Comedero Automático</option>
                        <option value="Otros">Otros</option>
                    </select>
                </div>

                {{-- Categoría --}}
                <div>
                    <label for="categoria" class="block text-sm font-medium text-zinc-300 mb-1">
                        Categoría <span class="text-red-500">*</span>
                    </label>
                    <input type="text" id="categoria" name="categoria"
                        class="w-full px-4 py-3 bg-zinc-800 border border-zinc-700 rounded-lg text-white"
                        placeholder="Ej: Higiene, Medicamentos..." required>
                </div>

                {{-- Stock --}}
                <div>
                    <label for="stock" class="block text-sm font-medium text-zinc-300 mb-1">
                        Stock <span class="text-red-500">*</span>
                    </label>
                    <input type="number" id="stock" name="stock" min="0"
                        class="w-full px-4 py-3 bg-zinc-800 border border-zinc-700 rounded-lg text-white"
                        placeholder="Ej: 100" required>
                </div>

                {{-- Precio --}}
                <div>
                    <label for="precio" class="block text-sm font-medium text-zinc-300 mb-1">
                        Precio (S/.) <span class="text-red-500">*</span>
                    </label>
                    <input type="number" id="precio" name="precio" step="0.01" min="0"
                        class="w-full px-4 py-3 bg-zinc-800 border border-zinc-700 rounded-lg text-white"
                        placeholder="Ej: 45.00" required>
                </div>

                {{-- Descripción --}}
                <div class="md:col-span-2">
                    <label for="descripcion" class="block text-sm font-medium text-zinc-300 mb-1">
                        Descripción
                    </label>
                    <textarea id="descripcion" name="descripcion" rows="3"
                        class="w-full px-4 py-3 bg-zinc-800 border border-zinc-700 rounded-lg text-white"
                        placeholder="Describe brevemente el producto (opcional)"></textarea>
                </div>
            </div>

            {{-- Separador --}}
            <div class="relative my-8">
                <div class="absolute inset-0 flex items-center">
                    <div class="w-full border-t border-zinc-800"></div>
                </div>
            </div>

            {{-- Nota --}}
            <div class="text-sm text-zinc-500 mb-6">
                Los campos marcados con <span class="text-red-500 font-bold">*</span> son obligatorios.
            </div>

            {{-- Botón --}}
            <div class="flex justify-end">
                <button type="submit"
                    class="px-6 py-3 bg-blue-600 text-white font-medium rounded-lg hover:bg-blue-700 shadow-lg">
                    Registrar Producto
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const productMap = {
            'Shampoo Antipulgas': {
                categoria: 'Higiene',
                precio: 35,
                descripcion: 'Shampoo especializado para eliminar y prevenir pulgas en mascotas.'
            },
            'Juguete para Perro': {
                categoria: 'Juguetes',
                precio: 20,
                descripcion: 'Juguete interactivo y resistente para perros activos.'
            },
            'Antibiótico': {
                categoria: 'Medicamentos',
                precio: 60,
                descripcion: 'Medicamento recetado para tratar infecciones bacterianas en mascotas.'
            },
            'Vitaminas': {
                categoria: 'Suplementos',
                precio: 25,
                descripcion: 'Vitaminas para fortalecer la salud general de tu mascota.'
            },
            'Arena para Gato': {
                categoria: 'Limpieza',
                precio: 18,
                descripcion: 'Arena absorbente para caja de gatos, con control de olores.'
            },
            'Peine para Mascotas': {
                categoria: 'Higiene',
                precio: 15,
                descripcion: 'Peine con cerdas suaves para el cepillado diario del pelaje.'
            },
            'Collar Antipulgas': {
                categoria: 'Accesorios',
                precio: 45,
                descripcion: 'Collar protector que repele pulgas y garrapatas por varias semanas.'
            },
            'Desparasitante': {
                categoria: 'Medicamentos',
                precio: 50,
                descripcion: 'Solución oral para eliminar parásitos internos.'
            },
            'Galletas para Perros': {
                categoria: 'Alimentos',
                precio: 22,
                descripcion: 'Galletas nutritivas y deliciosas como premio o complemento.'
            },
            'Comedero Automático': {
                categoria: 'Accesorios',
                precio: 120,
                descripcion: 'Dispensador automático de alimento seco programable.'
            },
            'Otros': {
                categoria: '',
                precio: 0,
                descripcion: ''
            }
        };

        const nombreSelect = document.getElementById('nombre');
        const categoriaInput = document.getElementById('categoria');
        const precioInput = document.getElementById('precio');
        const descripcionInput = document.getElementById('descripcion');

        nombreSelect.addEventListener('change', function () {
            const selected = this.value;
            const data = productMap[selected];

            if (data) {
                categoriaInput.value = data.categoria;
                precioInput.value = data.precio;
                descripcionInput.value = `Producto: ${selected}. ${data.descripcion}`;
            }
        });
    });
</script>

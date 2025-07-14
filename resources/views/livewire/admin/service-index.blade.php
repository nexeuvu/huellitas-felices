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
            Registrar Nuevo Servicio
        </h1>

        <form action="{{ route('admin.service.store') }}" method="POST" class="space-y-6">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                {{-- Nombre del Servicio --}}
                <div class="md:col-span-2">
                    <label for="nombre" class="block text-sm font-medium text-zinc-300 mb-1">
                        Tipo de Servicio <span class="text-red-500">*</span>
                    </label>
                    <select id="nombre" name="nombre"
                        class="w-full px-4 py-3 bg-zinc-800 border border-zinc-700 rounded-lg text-white"
                        required>
                        <option value="" disabled selected>Seleccione un tipo de servicio</option>
                        <option value="Vacunación">Vacunación</option>
                        <option value="Baño">Baño</option>
                        <option value="Corte de Uñas">Corte de Uñas</option>
                        <option value="Desparasitación">Desparasitación</option>
                        <option value="Consulta General">Consulta General</option>
                        <option value="Cirugía">Cirugía</option>
                        <option value="Radiografía">Radiografía</option>
                        <option value="Ecografía">Ecografía</option>
                        <option value="Peluquería">Peluquería</option>
                        <option value="Emergencia 24h">Emergencia 24h</option>
                        <option value="Guardería">Guardería</option>
                        <option value="Otros">Otros</option>
                    </select>
                </div>

                {{-- Duración (min) --}}
                <div>
                    <label for="duracion_min" class="block text-sm font-medium text-zinc-300 mb-1">
                        Duración (minutos) <span class="text-red-500">*</span>
                    </label>
                    <input type="number" id="duracion_min" name="duracion_min" min="0"
                        class="w-full px-4 py-3 bg-zinc-800 border border-zinc-700 rounded-lg text-white"
                        placeholder="Ej: 30, 45, 60..." required>
                </div>

                {{-- Costo --}}
                <div>
                    <label for="costo" class="block text-sm font-medium text-zinc-300 mb-1">
                        Costo (S/.) <span class="text-red-500">*</span>
                    </label>
                    <input type="number" id="costo" name="costo" step="0.01" min="0"
                        class="w-full px-4 py-3 bg-zinc-800 border border-zinc-700 rounded-lg text-white"
                        placeholder="Ej: 60.00" required>
                </div>

                {{-- Descripción --}}
                <div class="md:col-span-2">
                    <label for="descripcion" class="block text-sm font-medium text-zinc-300 mb-1">
                        Descripción
                    </label>
                    <textarea id="descripcion" name="descripcion" rows="3"
                        class="w-full px-4 py-3 bg-zinc-800 border border-zinc-700 rounded-lg text-white"
                        placeholder="Describe brevemente el servicio (opcional)"></textarea>
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
                    Registrar Servicio
                </button>
            </div>
        </form>
    </div>
</div>

{{-- Script de autocompletado --}}
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const serviceMap = {
            'Vacunación':       { duracion: 15, costo: 80,  descripcion: 'Aplicación de vacunas según el plan veterinario.' },
            'Baño':             { duracion: 45, costo: 40,  descripcion: 'Baño completo con productos especializados.' },
            'Corte de Uñas':    { duracion: 10, costo: 25,  descripcion: 'Recorte higiénico y seguro de uñas.' },
            'Desparasitación':  { duracion: 20, costo: 60,  descripcion: 'Tratamiento para parásitos internos y externos.' },
            'Consulta General': { duracion: 30, costo: 70,  descripcion: 'Evaluación clínica general del paciente.' },
            'Cirugía':          { duracion: 120, costo: 300, descripcion: 'Procedimiento quirúrgico especializado según diagnóstico.' },
            'Radiografía':      { duracion: 25, costo: 120, descripcion: 'Estudio radiológico para diagnóstico.' },
            'Ecografía':        { duracion: 30, costo: 150, descripcion: 'Diagnóstico por imagen de tejidos blandos.' },
            'Peluquería':       { duracion: 60, costo: 50,  descripcion: 'Corte y arreglo estético de pelaje.' },
            'Emergencia 24h':   { duracion: 60, costo: 200, descripcion: 'Atención médica inmediata de urgencias.' },
            'Guardería':        { duracion: 240, costo: 100, descripcion: 'Cuidado supervisado por horas.' },
            'Otros':            { duracion: 30, costo: 0,    descripcion: 'Servicio personalizado o no listado.' }
        };

        const select = document.getElementById('nombre');
        const inputDuracion = document.getElementById('duracion_min');
        const inputCosto = document.getElementById('costo');
        const inputDescripcion = document.getElementById('descripcion');

        select.addEventListener('change', function () {
            const selected = this.value;
            const data = serviceMap[selected];

            if (data) {
                inputDuracion.value = data.duracion;
                inputCosto.value = data.costo;
                inputDescripcion.value = `Servicio de ${selected}. ${data.descripcion}`;
            }
        });
    });
</script>
<?php

namespace App\Http\Controllers\Admin;

use App\Exports\EmployeesExport;
use App\Http\Controllers\Controller;
use App\Models\Employee;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Maatwebsite\Excel\Facades\Excel;

class EmployeeController extends Controller
{
    public function index()
    {
        return view('Admin.employee.index');
    }

    public function create()
    {
        return view('admin.employee.create');
    }

    public function consultarDni(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'dni' => 'required|digits:8',
            'tipo_documento' => 'required|in:DNI,CE',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'error' => $validator->errors()->first()
            ], 422);
        }

        $dni = $request->input('dni');
        $tipoDocumento = $request->input('tipo_documento');
        $url = "https://api.apis.net.pe/v2/reniec/dni?numero={$dni}";

        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . env('APIS_NET_PE_TOKEN'),
                'Accept' => 'application/json',
            ])->withOptions([
                'verify' => false, // Solo para pruebas locales
            ])->get($url);

            if ($response->successful()) {
                $data = $response->json();
                
                Log::info('Respuesta de la API para documento', [
                    'dni' => $dni,
                    'tipo_documento' => $tipoDocumento,
                    'response' => $data
                ]);

                $normalizedData = [
                    'tipo_documento' => $tipoDocumento,
                    'dni' => $data['numeroDocumento'] ?? $dni,
                    'nombres' => $data['nombres'] ?? '',
                    'apellidos' => $this->normalizarApellidos($data),
                    'digito_verificador' => $data['digitoVerificador'] ?? '',
                ];

                return response()->json($normalizedData);
            } else {
                $error = $response->json()['error'] ?? 'Respuesta no válida';
                Log::error('Error en la consulta a la API', [
                    'dni' => $dni,
                    'status' => $response->status(),
                    'error' => $error
                ]);
                return response()->json([
                    'error' => 'No se pudo consultar el documento: ' . $error
                ], 400);
            }
        } catch (\Exception $e) {
            Log::error('Excepción al consultar la API', [
                'dni' => $dni,
                'error' => $e->getMessage()
            ]);
            return response()->json([
                'error' => 'Error al consultar el documento: ' . $e->getMessage()
            ], 500);
        }
    }

    private function normalizarApellidos(array $data): string
    {
        if (isset($data['apellidos']) && !empty($data['apellidos'])) {
            return $data['apellidos'];
        }

        $apellidoPaterno = $data['apellidoPaterno'] ?? '';
        $apellidoMaterno = $data['apellidoMaterno'] ?? '';
        $apellidos = trim("{$apellidoPaterno} {$apellidoMaterno}");

        if (empty($apellidos)) {
            $apellidos = $data['nombreCompleto'] ?? $data['apellido'] ?? $data['apellidos_completos'] ?? '';
            if (!empty($apellidos) && isset($data['nombres'])) {
                $apellidos = str_replace($data['nombres'], '', $apellidos);
                $apellidos = trim($apellidos);
            }
        }

        return $apellidos;
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'tipo_documento' => 'required|in:DNI,CE,PASAPORTE',
            'dni' => 'required|string|max:20|unique:employees,dni',
            'nombres' => 'required|string|max:100',
            'apellidos' => 'required|string|max:100',
            'direccion' => 'nullable|string|max:200',
            'telefono' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:100|unique:employees,email',
            'fecha_contratacion' => 'required|date',
            'puesto' => 'required|string|max:50'
        ]);

        try {
            $validator->validate();

            Employee::create([
                'tipo_documento' => $request->tipo_documento,
                'dni' => $request->dni,
                'nombres' => $request->nombres,
                'apellidos' => $request->apellidos,
                'direccion' => $request->direccion,
                'telefono' => $request->telefono,
                'email' => $request->email,
                'fecha_contratacion' => $request->fecha_contratacion,
                'puesto' => $request->puesto
            ]);

            return redirect()->route('admin.employee.index')
                ->with('success', 'El empleado fue registrado correctamente.');
        } catch (ValidationException $e) {
            return back()->withErrors($e->validator->errors())->withInput();
        }
    }

    public function update(Request $request, string $id)
    {
        $employee = Employee::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'tipo_documento' => 'required|in:DNI,CE,PASAPORTE',
            'dni' => 'required|string|max:20|unique:employees,dni,'.$employee->id,
            'nombres' => 'required|string|max:100',
            'apellidos' => 'required|string|max:100',
            'direccion' => 'nullable|string|max:200',
            'telefono' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:100|unique:employees,email,'.$employee->id,
            'fecha_contratacion' => 'required|date',
            'puesto' => 'required|string|max:50'
        ]);

        try {
            $validator->validate();

            $employee->update([
                'tipo_documento' => $request->tipo_documento,
                'dni' => $request->dni,
                'nombres' => $request->nombres,
                'apellidos' => $request->apellidos,
                'direccion' => $request->direccion,
                'telefono' => $request->telefono,
                'email' => $request->email,
                'fecha_contratacion' => $request->fecha_contratacion,
                'puesto' => $request->puesto
            ]);

            return redirect()->route('admin.employee.index')
                ->with('success', 'El empleado se actualizó correctamente.');
        } catch (ValidationException $e) {
            return back()->withErrors($e->validator->errors())->withInput();
        }
    }

    public function destroy(string $id)
    {
        Employee::findOrFail($id)->delete();
        return redirect()->route('admin.employee.index')
            ->with('success', 'El empleado fue eliminado correctamente.');
    }

    public function exportPdf()
    {
        $employees = Employee::orderBy('apellidos')->get();
        $pdf = Pdf::loadView('admin.employee.pdf', compact('employees'));
        return $pdf->download('reporte_empleados.pdf');
    }

    public function exportExcel()
    {
        return Excel::download(new EmployeesExport, 'reporte_empleados.xlsx');
    }
}
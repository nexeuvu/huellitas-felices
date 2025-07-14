<?php

namespace App\Http\Controllers\Admin;

use App\Exports\CustomersExport;
use App\Http\Controllers\Controller;
use App\Models\Customer;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Maatwebsite\Excel\Facades\Excel;

class CustomerController extends Controller
{
    public function index()
    {
        return view('Admin.customer.index');
    }

    public function create()
    {
        return view('admin.customer.create');
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
            'tipo_documento' => 'required|in:DNI,RUC,CE,PASAPORTE',
            'dni' => 'required|string|max:20|unique:customers,dni',
            'nombres' => 'required|string|max:100',
            'apellidos' => 'required|string|max:100',
            'direccion' => 'nullable|string|max:200',
            'telefono' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:100|unique:customers,email',
            'fecha_registro' => 'required|date'
        ]);

        try {
            $validator->validate();

            Customer::create([
                'tipo_documento' => $request->tipo_documento,
                'dni' => $request->dni,
                'nombres' => $request->nombres,
                'apellidos' => $request->apellidos,
                'direccion' => $request->direccion,
                'telefono' => $request->telefono,
                'email' => $request->email,
                'fecha_registro' => $request->fecha_registro
            ]);

            return redirect()->route('admin.customer.index')
                ->with('success', 'El cliente fue registrado correctamente.');
        } catch (ValidationException $e) {
            return back()->withErrors($e->validator->errors())->withInput();
        }
    }

    public function update(Request $request, string $id)
    {
        $customer = Customer::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'tipo_documento' => 'required|in:DNI,RUC,CE,PASAPORTE',
            'dni' => 'required|string|max:20|unique:customers,dni,'.$customer->id,
            'nombres' => 'required|string|max:100',
            'apellidos' => 'required|string|max:100',
            'direccion' => 'nullable|string|max:200',
            'telefono' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:100|unique:customers,email,'.$customer->id,
            'fecha_registro' => 'required|date'
        ]);

        try {
            $validator->validate();

            $customer->update([
                'tipo_documento' => $request->tipo_documento,
                'dni' => $request->dni,
                'nombres' => $request->nombres,
                'apellidos' => $request->apellidos,
                'direccion' => $request->direccion,
                'telefono' => $request->telefono,
                'email' => $request->email,
                'fecha_registro' => $request->fecha_registro
            ]);

            return redirect()->route('admin.customer.index')
                ->with('success', 'El cliente se actualizó correctamente.');
        } catch (ValidationException $e) {
            return back()->withErrors($e->validator->errors())->withInput();
        }
    }

    public function destroy(string $id)
    {
        Customer::findOrFail($id)->delete();
        return redirect()->route('admin.customer.index')
            ->with('success', 'El cliente fue eliminado correctamente.');
    }

    public function exportPdf()
    {
        $customers = Customer::orderBy('apellidos')->get();
        $pdf = Pdf::loadView('admin.customer.pdf', compact('customers'));
        return $pdf->download('reporte_clientes.pdf');
    }

    public function exportExcel()
    {
        return Excel::download(new CustomersExport, 'reporte_clientes.xlsx');
    }
}
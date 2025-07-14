<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\ServicesExport;

class ServiceController extends Controller
{
    public function index()
    {
        return view('Admin.service.index');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nombre' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'duracion_min' => 'required|integer|min:0',
            'costo' => 'required|numeric|min:0',
        ]);

        try {
            $validator->validate();

            Service::create([
                'nombre' => $request->nombre,
                'descripcion' => $request->descripcion,
                'duracion_min' => $request->duracion_min,
                'costo' => $request->costo,
            ]);

            return redirect()->route('admin.service.index')
                ->with('success', 'El servicio fue registrado correctamente.');
        } catch (ValidationException $e) {
            return back()->withErrors($e->validator->errors())->withInput();
        }
    }

    public function update(Request $request, string $id)
    {
        $validator = Validator::make($request->all(), [
            'nombre' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'duracion_min' => 'required|integer|min:0',
            'costo' => 'required|numeric|min:0',
        ]);

        try {
            $validator->validate();

            $service = Service::findOrFail($id);
            $service->update([
                'nombre' => $request->nombre,
                'descripcion' => $request->descripcion,
                'duracion_min' => $request->duracion_min,
                'costo' => $request->costo,
            ]);

            return redirect()->route('admin.service.index')
                ->with('success', 'El servicio fue actualizado correctamente.');
        } catch (ValidationException $e) {
            return back()->withErrors($e->validator->errors())->withInput();
        }
    }

    public function destroy(string $id)
    {
        $service = Service::findOrFail($id);
        $service->delete();

        return redirect()->route('admin.service.index')
            ->with('success', 'El servicio fue eliminado correctamente.');
    }

    public function exportPdf()
    {
        $services = Service::orderBy('nombre')->get();
        $pdf = Pdf::loadView('admin.service.pdf', compact('services'));
        return $pdf->download('reporte_servicios.pdf');
    }

    public function exportExcel()
    {
        return Excel::download(new ServicesExport, 'reporte_servicios.xlsx');
    }
}

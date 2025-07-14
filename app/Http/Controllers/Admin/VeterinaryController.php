<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Veterinary;
use App\Models\Employee;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\VeterinariesExport;

class VeterinaryController extends Controller
{
    public function index()
    {
        return view('Admin.veterinary.index');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'employee_id'   => 'required|exists:employees,id|unique:veterinaries,employee_id',
            'especialidad'  => 'required|string|max:100',
            'licencia'      => 'required|string|max:50|unique:veterinaries,licencia'
        ]);

        try {
            $validator->validate();

            Veterinary::create([
                'employee_id'  => $request->employee_id,
                'especialidad' => $request->especialidad,
                'licencia'     => $request->licencia,
            ]);

            return redirect()->route('admin.veterinary.index')
                ->with('success', 'Veterinario registrado correctamente.');
        } catch (ValidationException $e) {
            return back()->withErrors($e->validator->errors())->withInput();
        }
    }
    
    public function update(Request $request, $id)
    {
        $veterinary = Veterinary::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'employee_id'   => 'required|exists:employees,id|unique:veterinaries,employee_id,' . $veterinary->id,
            'especialidad'  => 'required|string|max:100',
            'licencia'      => 'required|string|max:50|unique:veterinaries,licencia,' . $veterinary->id,
        ]);

        try {
            $validator->validate();

            $veterinary->update([
                'employee_id'  => $request->employee_id,
                'especialidad' => $request->especialidad,
                'licencia'     => $request->licencia,
            ]);

            return redirect()->route('admin.veterinary.index')
                ->with('success', 'Veterinario actualizado correctamente.');
        } catch (ValidationException $e) {
            return back()->withErrors($e->validator->errors())->withInput();
        }
    }

    public function destroy($id)
    {
        Veterinary::findOrFail($id)->delete();
        return redirect()->route('admin.veterinary.index')
            ->with('success', 'Veterinario eliminado correctamente.');
    }

    public function exportPdf()
    {
        $veterinaries = Veterinary::with('employee')->orderBy('id')->get();
        $pdf = Pdf::loadView('admin.veterinary.pdf', compact('veterinaries'));
        return $pdf->download('reporte_veterinarios.pdf');
    }

    public function exportExcel()
    {
        return Excel::download(new VeterinariesExport, 'reporte_veterinarios.xlsx');
    }
}

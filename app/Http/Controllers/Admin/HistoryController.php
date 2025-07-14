<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\History;
use App\Models\Pet;
use App\Models\Veterinary;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\HistoriesExport;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class HistoryController extends Controller
{
    public function index()
    {
        return view('Admin.history.index');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'pet_id' => 'required|exists:pets,id',
            'veterinary_id' => 'required|exists:veterinaries,id',
            'fecha' => 'required|date',
            'diagnostico' => 'required|string',
            'tratamiento' => 'required|string',
            'observaciones' => 'nullable|string',
        ]);

        try {
            $validator->validate();

            History::create($request->all());

            return redirect()->route('admin.history.index')
                ->with('success', 'La historia clínica fue registrada correctamente.');
        } catch (ValidationException $e) {
            return back()->withErrors($e->validator->errors())->withInput();
        }
    }

    public function update(Request $request, $id)
    {
        $history = History::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'pet_id' => 'required|exists:pets,id',
            'veterinary_id' => 'required|exists:veterinaries,id',
            'fecha' => 'required|date',
            'diagnostico' => 'required|string',
            'tratamiento' => 'required|string',
            'observaciones' => 'nullable|string',
        ]);

        try {
            $validator->validate();

            $history->update($request->all());

            return redirect()->route('admin.history.index')
                ->with('success', 'La historia clínica se actualizó correctamente.');
        } catch (ValidationException $e) {
            return back()->withErrors($e->validator->errors())->withInput();
        }
    }

    public function destroy($id)
    {
        History::findOrFail($id)->delete();

        return redirect()->route('admin.history.index')
            ->with('success', 'La historia clínica fue eliminada correctamente.');
    }

    public function exportPdf()
    {
        $histories = History::with('pet', 'veterinary.employee')->orderBy('fecha', 'desc')->get();
        $pdf = Pdf::loadView('admin.history.pdf', compact('histories'));
        return $pdf->download('reporte_historial_clinico.pdf');
    }

    public function exportExcel()
    {
        return Excel::download(new HistoriesExport, 'reporte_historial_clinico.xlsx');
    }

}

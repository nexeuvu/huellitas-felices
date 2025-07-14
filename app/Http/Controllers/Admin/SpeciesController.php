<?php

namespace App\Http\Controllers\Admin;

use App\Exports\SpeciesExport;
use App\Http\Controllers\Controller;
use App\Models\Species;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Maatwebsite\Excel\Facades\Excel;

class SpeciesController extends Controller
{
    public function index()
    {
        return view('Admin.species.index');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nombre' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
        ]);

        try {
            $validator->validate();

            Species::create([
                'nombre' => $request->nombre,
                'descripcion' => $request->descripcion,
            ]);

            return redirect()->route('admin.species.index')
                ->with('success', 'La especie fue registrada correctamente.');
        } catch (ValidationException $e) {
            return back()->withErrors($e->validator->errors())->withInput();
        }
    }

    public function update(Request $request, string $id)
    {
        $validator = Validator::make($request->all(), [
            'nombre' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
        ]);

        try {
            $validator->validate();

            $species = Species::findOrFail($id);
            $species->update([
                'nombre' => $request->nombre,
                'descripcion' => $request->descripcion,
            ]);

            return redirect()->route('admin.species.index')
                ->with('success', 'La especie fue actualizada correctamente.');
        } catch (ValidationException $e) {
            return back()->withErrors($e->validator->errors())->withInput();
        }
    }

    public function destroy(string $id)
    {
        $species = Species::findOrFail($id);
        $species->delete();

        return redirect()->route('admin.species.index')
            ->with('success', 'La especie fue eliminada correctamente.');
    }
    
    public function exportPdf()
    {
        $species = Species::orderBy('nombre')->get();
        $pdf = Pdf::loadView('admin.species.pdf', compact('species'));
        return $pdf->download('reporte_especies.pdf');
    }

    public function exportExcel()
    {
        return Excel::download(new SpeciesExport, 'reporte_especies.xlsx');
    }
}

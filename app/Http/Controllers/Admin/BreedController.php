<?php

namespace App\Http\Controllers\Admin;

use App\Exports\BreedsExport;
use App\Http\Controllers\Controller;
use App\Models\Breed;
use App\Models\Species;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Maatwebsite\Excel\Facades\Excel;

class BreedController extends Controller
{
    public function index()
    {
        return view('Admin.breed.index');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'species_id' => 'required|exists:species,id',
            'nombre' => 'required|string|max:255',
            'caracteristicas' => 'nullable|string',
        ]);

        try {
            $validator->validate();

            Breed::create($request->only('species_id', 'nombre', 'caracteristicas'));

            return redirect()->route('admin.breed.index')
                ->with('success', 'La raza fue registrada correctamente.');
        } catch (ValidationException $e) {
            return back()->withErrors($e->validator->errors())->withInput();
        }
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'species_id' => 'required|exists:species,id',
            'nombre' => 'required|string|max:255',
            'caracteristicas' => 'nullable|string',
        ]);

        try {
            $validator->validate();

            $breed = Breed::findOrFail($id);
            $breed->update($request->only('species_id', 'nombre', 'caracteristicas'));

            return redirect()->route('admin.breed.index')
                ->with('success', 'La raza fue actualizada correctamente.');
        } catch (ValidationException $e) {
            return back()->withErrors($e->validator->errors())->withInput();
        }
    }

    public function destroy($id)
    {
        $breed = Breed::findOrFail($id);
        $breed->delete();

        return redirect()->route('admin.breed.index')
            ->with('success', 'La raza fue eliminada correctamente.');
    }

    public function exportPdf()
    {
        $breeds = Breed::with('species')->orderBy('nombre')->get();
        $pdf = Pdf::loadView('admin.breed.pdf', compact('breeds'));
        return $pdf->download('reporte_razas.pdf');
    }

    public function exportExcel()
    {
        return Excel::download(new BreedsExport, 'reporte_razas.xlsx');
    }
}

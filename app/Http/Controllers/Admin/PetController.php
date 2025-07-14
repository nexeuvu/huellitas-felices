<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pet;
use App\Models\Customer;
use App\Models\Breed;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\PetsExport;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class PetController extends Controller
{
    public function index()
    {
        return view('Admin.pet.index');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'customer_id' => 'required|exists:customers,id',
            'breed_id' => 'required|exists:breeds,id',
            'nombre' => 'required|string|max:100',
            'fecha_nacimiento' => 'nullable|date',
            'genero' => 'required|in:Macho,Hembra',
            'color' => 'nullable|string|max:50',
            'peso' => 'nullable|numeric|min:0',
            'foto' => 'nullable|string|max:255',
        ]);

        try {
            $validator->validate();

            Pet::create($request->all());

            return redirect()->route('admin.pet.index')
                ->with('success', 'La mascota fue registrada correctamente.');
        } catch (ValidationException $e) {
            return back()->withErrors($e->validator->errors())->withInput();
        }
    }

    public function update(Request $request, $id)
    {
        $pet = Pet::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'customer_id' => 'required|exists:customers,id',
            'breed_id' => 'required|exists:breeds,id',
            'nombre' => 'required|string|max:100',
            'fecha_nacimiento' => 'nullable|date',
            'genero' => 'required|in:Macho,Hembra',
            'color' => 'nullable|string|max:50',
            'peso' => 'nullable|numeric|min:0',
            'foto' => 'nullable|string|max:255',
        ]);

        try {
            $validator->validate();

            $pet->update($request->all());

            return redirect()->route('admin.pet.index')
                ->with('success', 'La mascota se actualizó correctamente.');
        } catch (ValidationException $e) {
            return back()->withErrors($e->validator->errors())->withInput();
        }
    }

    public function destroy($id)
    {
        Pet::findOrFail($id)->delete();

        return redirect()->route('admin.pet.index')
            ->with('success', 'La mascota fue eliminada correctamente.');
    }

    public function exportPdf()
    {
        $pets = Pet::with('customer', 'breed')->orderBy('nombre')->get();
        $pdf = Pdf::loadView('admin.pet.pdf', compact('pets'));
        return $pdf->download('reporte_mascotas.pdf');
    }

    public function exportExcel()
    {
        return Excel::download(new PetsExport, 'reporte_mascotas.xlsx');
    }
}

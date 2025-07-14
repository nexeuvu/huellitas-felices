<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\Pet;
use App\Models\Service;
use App\Models\Veterinary;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\AppointmentsExport;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class AppointmentController extends Controller
{
    public function index()
    {
        return view('Admin.appointment.index');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'pet_id' => 'required|exists:pets,id',
            'veterinary_id' => 'required|exists:veterinaries,id',
            'service_id' => 'required|exists:services,id',
            'fecha_hora' => 'required|date|after_or_equal:now',
            'estado' => 'required|in:pendiente,confirmado,cancelado,completado',
            'notas' => 'nullable|string',
        ]);

        try {
            $validator->validate();

            Appointment::create($request->all());

            return redirect()->route('admin.appointment.index')
                ->with('success', 'La cita fue registrada correctamente.');
        } catch (ValidationException $e) {
            return back()->withErrors($e->validator->errors())->withInput();
        }
    }

    public function update(Request $request, $id)
    {
        $appointment = Appointment::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'pet_id' => 'required|exists:pets,id',
            'veterinary_id' => 'required|exists:veterinaries,id',
            'service_id' => 'required|exists:services,id',
            'fecha_hora' => 'required|date|after_or_equal:now',
            'estado' => 'required|in:pendiente,confirmado,cancelado,completado',
            'notas' => 'nullable|string',
        ]);

        try {
            $validator->validate();

            $appointment->update($request->all());

            return redirect()->route('admin.appointment.index')
                ->with('success', 'La cita se actualizó correctamente.');
        } catch (ValidationException $e) {
            return back()->withErrors($e->validator->errors())->withInput();
        }
    }

    public function destroy($id)
    {
        Appointment::findOrFail($id)->delete();

        return redirect()->route('admin.appointment.index')
            ->with('success', 'La cita fue eliminada correctamente.');
    }

    public function exportPdf()
    {
        $appointments = Appointment::with('pet', 'veterinary', 'service')->orderBy('fecha_hora')->get();
        $pdf = Pdf::loadView('admin.appointment.pdf', compact('appointments'));
        return $pdf->download('reporte_citas.pdf');
    }

    public function exportExcel()
    {
        return Excel::download(new AppointmentsExport, 'reporte_citas.xlsx');
    }
}

<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use App\Models\Customer;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\InvoicesExport;
use App\Models\Invoices;

class InvoicesController extends Controller
{
    public function index()
    {
        return view('Admin.invoices.index');
    }


    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'customer_id' => 'required|exists:customers,id',
            'fecha' => 'required|date',
            'sub_total' => 'required|numeric|min:0',
            'impuesto' => 'required|numeric|min:0',
            'total_metodo_pago' => 'required|numeric|min:0',
        ]);

        try {
            $validator->validate();

            Invoices::create([
                'customer_id' => $request->customer_id,
                'fecha' => $request->fecha,
                'sub_total' => $request->sub_total,
                'impuesto' => $request->impuesto,
                'total_metodo_pago' => $request->total_metodo_pago,
            ]);

            return redirect()->route('admin.invoices.index')
                ->with('success', 'La factura fue registrada correctamente.');
        } catch (ValidationException $e) {
            return back()->withErrors($e->validator->errors())->withInput();
        }
    }

    public function update(Request $request, string $id)
    {
        $invoice = Invoices::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'customer_id' => 'required|exists:customers,id',
            'fecha' => 'required|date',
            'sub_total' => 'required|numeric|min:0',
            'impuesto' => 'required|numeric|min:0',
            'total_metodo_pago' => 'required|numeric|min:0',
        ]);

        try {
            $validator->validate();

            $invoice->update([
                'customer_id' => $request->customer_id,
                'fecha' => $request->fecha,
                'sub_total' => $request->sub_total,
                'impuesto' => $request->impuesto,
                'total_metodo_pago' => $request->total_metodo_pago,
            ]);

            return redirect()->route('admin.invoices.index')
                ->with('success', 'La factura se actualizó correctamente.');
        } catch (ValidationException $e) {
            return back()->withErrors($e->validator->errors())->withInput();
        }
    }

    public function destroy(string $id)
    {
        Invoices::findOrFail($id)->delete();
        return redirect()->route('admin.invoices.index')
            ->with('success', 'La factura fue eliminada correctamente.');
    }

    public function exportPdf()
    {
        $invoices = Invoices::with('customer')->orderBy('fecha', 'desc')->get();
        $pdf = Pdf::loadView('admin.invoices.pdf', compact('invoices'));
        return $pdf->download('reporte_facturas.pdf');
    }

    public function exportExcel()
    {
        return Excel::download(new InvoicesExport, 'reporte_facturas.xlsx');
    }
}

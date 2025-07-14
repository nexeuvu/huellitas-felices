<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Invoices;
use App\Models\Service;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\InvoicesDetailExport;
use App\Models\Invoices_detail;

class Invoices_detailController extends Controller
{
    public function index()
    {
        return view('Admin.invoices_detail.index');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'invoices_id'     => 'required|exists:invoices,id',
            'service_id'      => 'nullable|exists:services,id',
            'product_id'      => 'nullable|exists:products,id',
            'cantidad'        => 'required|integer|min:1',
            'precio_unitario' => 'required|numeric|min:0',
            'sub_total'       => 'required|numeric|min:0',
            'total'           => 'required|numeric|min:0',
        ]);

        try {
            $validator->validate();

            Invoices_detail::create([
                'invoices_id'     => $request->invoices_id,
                'service_id'      => $request->service_id,
                'product_id'      => $request->product_id,
                'cantidad'        => $request->cantidad,
                'precio_unitario' => $request->precio_unitario,
                'sub_total'       => $request->sub_total,
                'total'           => $request->total,
            ]);

            return redirect()->route('admin.invoices_detail.index')
                ->with('success', 'El detalle de la factura fue registrado correctamente.');
        } catch (ValidationException $e) {
            return back()->withErrors($e->validator->errors())->withInput();
        }
    }

    public function update(Request $request, string $id)
    {
        $detail = Invoices_detail::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'invoices_id'     => 'required|exists:invoices,id',
            'service_id'      => 'nullable|exists:services,id',
            'product_id'      => 'nullable|exists:products,id',
            'cantidad'        => 'required|integer|min:1',
            'precio_unitario' => 'required|numeric|min:0',
            'sub_total'       => 'required|numeric|min:0',
            'total'           => 'required|numeric|min:0',
        ]);

        try {
            $validator->validate();

            $detail->update([
                'invoices_id'     => $request->invoices_id,
                'service_id'      => $request->service_id,
                'product_id'      => $request->product_id,
                'cantidad'        => $request->cantidad,
                'precio_unitario' => $request->precio_unitario,
                'sub_total'       => $request->sub_total,
                'total'           => $request->total,
            ]);

            return redirect()->route('admin.invoices_detail.index')
                ->with('success', 'El detalle de la factura se actualizó correctamente.');
        } catch (ValidationException $e) {
            return back()->withErrors($e->validator->errors())->withInput();
        }
    }

    public function destroy(string $id)
    {
        Invoices_detail::findOrFail($id)->delete();

        return redirect()->route('admin.invoices_detail.index')
            ->with('success', 'El detalle de la factura fue eliminado correctamente.');
    }

    public function exportPdf()
    {
        $details = Invoices_detail::with(['invoice.customer', 'service', 'product'])->get();
        $pdf = Pdf::loadView('admin.invoices_detail.pdf', compact('details'));
        return $pdf->download('reporte_detalle_facturas.pdf');
    }

    public function exportExcel()
    {
        return Excel::download(new InvoicesDetailExport, 'detalle_facturas.xlsx');
    }
}

<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\ProductsExport;

class ProductController extends Controller
{
    public function index()
    {
        return view('Admin.product.index');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nombre' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'categoria' => 'required|string|max:100',
            'stock' => 'required|integer|min:0',
            'precio' => 'required|numeric|min:0',
        ]);

        try {
            $validator->validate();

            Product::create([
                'nombre' => $request->nombre,
                'descripcion' => $request->descripcion,
                'categoria' => $request->categoria,
                'stock' => $request->stock,
                'precio' => $request->precio,
            ]);

            return redirect()->route('admin.product.index')
                ->with('success', 'El producto fue registrado correctamente.');
        } catch (ValidationException $e) {
            return back()->withErrors($e->validator->errors())->withInput();
        }
    }

    public function update(Request $request, string $id)
    {
        $validator = Validator::make($request->all(), [
            'nombre' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'categoria' => 'required|string|max:100',
            'stock' => 'required|integer|min:0',
            'precio' => 'required|numeric|min:0',
        ]);

        try {
            $validator->validate();

            $product = Product::findOrFail($id);
            $product->update([
                'nombre' => $request->nombre,
                'descripcion' => $request->descripcion,
                'categoria' => $request->categoria,
                'stock' => $request->stock,
                'precio' => $request->precio,
            ]);

            return redirect()->route('admin.product.index')
                ->with('success', 'El producto fue actualizado correctamente.');
        } catch (ValidationException $e) {
            return back()->withErrors($e->validator->errors())->withInput();
        }
    }

    public function destroy(string $id)
    {
        $product = Product::findOrFail($id);
        $product->delete();

        return redirect()->route('admin.product.index')
            ->with('success', 'El producto fue eliminado correctamente.');
    }

    public function exportPdf()
    {
        $products = Product::orderBy('nombre')->get();
        $pdf = Pdf::loadView('admin.product.pdf', compact('products'));
        return $pdf->download('reporte_productos.pdf');
    }

    public function exportExcel()
    {
        return Excel::download(new ProductsExport, 'reporte_productos.xlsx');
    }
}

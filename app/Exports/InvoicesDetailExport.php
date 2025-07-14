<?php

namespace App\Exports;

use App\Models\Invoices_detail;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

class InvoicesDetailExport implements FromCollection, WithHeadings, WithStyles, ShouldAutoSize
{
    public function collection()
    {
        return Invoices_detail::with(['invoice.customer', 'service', 'product'])
            ->orderByDesc('created_at')
            ->get()
            ->map(function ($detail) {
                $cliente = $detail->invoice->customer ?? null;
                return [
                    'ID' => $detail->id,
                    'Factura ID' => $detail->invoices_id,
                    'Cliente' => $cliente ? $cliente->nombres . ' ' . $cliente->apellidos : '-',
                    'Producto' => $detail->product->nombre ?? '-',
                    'Servicio' => $detail->service->nombre ?? '-',
                    'Cantidad' => $detail->cantidad,
                    'Precio Unitario (S/.)' => number_format($detail->precio_unitario, 2),
                    'Subtotal (S/.)' => number_format($detail->sub_total, 2),
                    'Total (S/.)' => number_format($detail->total, 2),
                ];
            });
    }

    public function headings(): array
    {
        return [
            '#',
            'Factura ID',
            'Cliente',
            'Producto',
            'Servicio',
            'Cantidad',
            'Precio Unitario (S/.)',
            'Subtotal (S/.)',
            'Total (S/.)',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        $highestColumn = $sheet->getHighestColumn();
        $highestRow = $sheet->getHighestRow();

        for ($i = 1; $i <= $highestRow; $i++) {
            $sheet->getRowDimension($i)->setRowHeight(-1);
        }

        return [
            1 => [
                'font' => ['bold' => true, 'size' => 12, 'color' => ['argb' => 'FFFFFFFF']],
                'fill' => ['fillType' => 'solid', 'color' => ['argb' => 'FF1A73E8']],
                'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
            ],
            "A1:{$highestColumn}{$highestRow}" => [
                'borders' => [
                    'allBorders' => ['borderStyle' => 'thin', 'color' => ['argb' => 'FF000000']],
                ],
                'alignment' => ['vertical' => Alignment::VERTICAL_CENTER],
            ],
        ];
    }
}

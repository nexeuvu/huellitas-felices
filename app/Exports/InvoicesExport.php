<?php

namespace App\Exports;

use App\Models\Invoices;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

class InvoicesExport implements FromCollection, WithHeadings, WithStyles, ShouldAutoSize
{
    public function collection()
    {
        return Invoices::with('customer')
            ->orderByDesc('fecha')
            ->get()
            ->map(function ($invoice) {
                return [
                    'ID' => $invoice->id,
                    'Cliente' => $invoice->customer ? $invoice->customer->nombres . ' ' . $invoice->customer->apellidos : '-',
                    'DNI' => $invoice->customer->dni ?? '-',
                    'Fecha' => optional($invoice->fecha)->format('d/m/Y'),
                    'Subtotal (S/.)' => number_format($invoice->sub_total, 2),
                    'Impuesto (S/.)' => number_format($invoice->impuesto, 2),
                    'Total (S/.)' => number_format($invoice->total_metodo_pago, 2),
                ];
            });
    }

    public function headings(): array
    {
        return [
            '#',
            'Cliente',
            'DNI',
            'Fecha',
            'Subtotal (S/.)',
            'Impuesto (S/.)',
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

<?php

namespace App\Exports;

use App\Models\History;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

class HistoriesExport implements FromCollection, WithHeadings, WithStyles, ShouldAutoSize
{
    public function collection()
    {
        return History::with(['pet', 'veterinary.employee'])
            ->orderByDesc('fecha')
            ->get()
            ->map(function ($history) {
                return [
                    'ID' => $history->id,
                    'Mascota' => $history->pet->nombre ?? '-',
                    'Veterinario' => optional($history->veterinary->employee)->nombres . ' ' . optional($history->veterinary->employee)->apellidos ?? '-',
                    'Fecha' => optional($history->fecha)->format('d/m/Y'),
                    'Diagnóstico' => $history->diagnostico,
                    'Tratamiento' => $history->tratamiento,
                    'Observaciones' => $history->observaciones ?? '-',
                ];
            });
    }

    public function headings(): array
    {
        return [
            '#',
            'Mascota',
            'Veterinario',
            'Fecha',
            'Diagnóstico',
            'Tratamiento',
            'Observaciones',
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

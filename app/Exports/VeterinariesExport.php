<?php

namespace App\Exports;

use App\Models\Veterinary;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

class VeterinariesExport implements FromCollection, WithHeadings, WithStyles, ShouldAutoSize
{
    public function collection()
    {
        return Veterinary::with('employee')->orderBy('id')->get()->map(function ($veterinary) {
            $empleado = $veterinary->employee;
            return [
                'ID' => $veterinary->id,
                'Empleado' => $empleado ? $empleado->nombres . ' ' . $empleado->apellidos : '-',
                'DNI' => $empleado->dni ?? '-',
                'Especialidad' => $veterinary->especialidad,
                'Licencia' => $veterinary->licencia,
            ];
        });
    }

    public function headings(): array
    {
        return [
            '#',
            'Empleado',
            'DNI',
            'Especialidad',
            'Licencia',
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

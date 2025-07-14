<?php

namespace App\Exports;

use App\Models\Pet;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

class PetsExport implements FromCollection, WithHeadings, WithStyles, ShouldAutoSize
{
    public function collection()
    {
        return Pet::with(['customer', 'breed'])->orderBy('nombre')->get()->map(function ($pet) {
            return [
                'ID' => $pet->id,
                'Nombre' => $pet->nombre,
                'Cliente' => $pet->customer ? $pet->customer->nombres . ' ' . $pet->customer->apellidos : '-',
                'Raza' => $pet->breed->nombre ?? '-',
                'Género' => $pet->genero,
                'Color' => $pet->color ?? '-',
                'Peso (kg)' => $pet->peso ? number_format($pet->peso, 2) : '-',
                'Fecha de Nacimiento' => optional($pet->fecha_nacimiento)->format('d/m/Y'),
            ];
        });
    }

    public function headings(): array
    {
        return [
            '#',
            'Nombre',
            'Cliente',
            'Raza',
            'Género',
            'Color',
            'Peso (kg)',
            'Fecha de Nacimiento'
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

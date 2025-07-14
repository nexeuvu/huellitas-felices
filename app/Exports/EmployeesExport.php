<?php

namespace App\Exports;

use App\Models\Employee;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

class EmployeesExport implements FromCollection, WithHeadings, WithStyles, ShouldAutoSize
{
    public function collection()
    {
        return Employee::orderBy('apellidos')->get()->map(function ($employee) {
            return [
                'ID' => $employee->id,
                'Tipo Documento' => $employee->tipo_documento,
                'DNI' => $employee->dni,
                'Nombres' => $employee->nombres,
                'Apellidos' => $employee->apellidos,
                'Dirección' => $employee->direccion ?? '-',
                'Teléfono' => $employee->telefono ?? '-',
                'Email' => $employee->email ?? '-',
                'Puesto' => $employee->puesto ?? '-',
                'Fecha Contratación' => optional($employee->fecha_contratacion)->format('d/m/Y'),
            ];
        });
    }

    public function headings(): array
    {
        return [
            '#',
            'Tipo Documento',
            'DNI',
            'Nombres',
            'Apellidos',
            'Dirección',
            'Teléfono',
            'Email',
            'Puesto',
            'Fecha Contratación'
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

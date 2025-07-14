<?php

namespace App\Exports;

use App\Models\Customer;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

class CustomersExport implements FromCollection, WithHeadings, WithStyles, ShouldAutoSize
{
    public function collection()
    {
        return Customer::orderBy('apellidos')->get()->map(function ($customer) {
            return [
                'ID' => $customer->id,
                'Tipo Documento' => $customer->tipo_documento,
                'DNI' => $customer->dni,
                'Nombres' => $customer->nombres,
                'Apellidos' => $customer->apellidos,
                'Dirección' => $customer->direccion ?? '-',
                'Teléfono' => $customer->telefono ?? '-',
                'Email' => $customer->email ?? '-',
                'Fecha Registro' => optional($customer->fecha_registro)->format('d/m/Y'),
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
            'Fecha Registro'
        ];
    }

    public function styles(Worksheet $sheet)
    {
        $highestColumn = $sheet->getHighestColumn();
        $highestRow = $sheet->getHighestRow();

        // Ajuste automático del alto de filas
        for ($i = 1; $i <= $highestRow; $i++) {
            $sheet->getRowDimension($i)->setRowHeight(-1);
        }

        return [
            // Estilo del encabezado
            1 => [
                'font' => ['bold' => true, 'size' => 12, 'color' => ['argb' => 'FFFFFFFF']],
                'fill' => ['fillType' => 'solid', 'color' => ['argb' => 'FF1A73E8']],
                'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
            ],
            // Bordes generales
            "A1:{$highestColumn}{$highestRow}" => [
                'borders' => [
                    'allBorders' => ['borderStyle' => 'thin', 'color' => ['argb' => 'FF000000']],
                ],
                'alignment' => ['vertical' => Alignment::VERTICAL_CENTER],
            ],
        ];
    }
}

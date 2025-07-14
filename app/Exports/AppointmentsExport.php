<?php

namespace App\Exports;

use App\Models\Appointment;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

class AppointmentsExport implements FromCollection, WithHeadings, WithStyles, ShouldAutoSize
{
    public function collection()
    {
        return Appointment::with(['pet.customer', 'veterinary.employee', 'service'])
            ->orderBy('fecha_hora')->get()->map(function ($appointment) {
                return [
                    'ID' => $appointment->id,
                    'Mascota' => $appointment->pet->nombre ?? '-',
                    'Cliente' => $appointment->pet->customer->nombres . ' ' . $appointment->pet->customer->apellidos ?? '-',
                    'Veterinario' => $appointment->veterinary->employee->nombres . ' ' . $appointment->veterinary->employee->apellidos ?? '-',
                    'Servicio' => $appointment->service->nombre ?? '-',
                    'Fecha y Hora' => optional($appointment->fecha_hora)->format('d/m/Y H:i'),
                    'Estado' => ucfirst($appointment->estado),
                    'Notas' => $appointment->notas ?? '-',
                ];
            });
    }

    public function headings(): array
    {
        return [
            '#',
            'Mascota',
            'Cliente',
            'Veterinario',
            'Servicio',
            'Fecha y Hora',
            'Estado',
            'Notas',
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

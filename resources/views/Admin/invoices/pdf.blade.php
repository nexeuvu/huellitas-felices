<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Reporte de Facturas</title>
    <style>
        :root {
            --primary-color: #1a73e8;
            --header-bg: #e0e0e0;
            --even-row-bg: #f5f5f5;
            --text-color: #333;
            --footer-color: #777;
            --border-color: #ccc;
            --table-font-size: 12px;
        }

        * { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 15mm;
            color: var(--text-color);
            font-size: var(--table-font-size);
            line-height: 1.5;
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
        }

        .logo img {
            max-width: 140px;
            opacity: 0.9;
            margin-bottom: 10px;
        }

        h1 {
            color: var(--primary-color);
            font-size: 22px;
            margin-bottom: 10px;
            font-weight: 600;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }

        th, td {
            border: 1px solid var(--border-color);
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: var(--header-bg);
            text-transform: uppercase;
            font-size: 11px;
        }

        tbody tr:nth-child(even) {
            background-color: var(--even-row-bg);
        }

        .text-center { text-align: center; }
        .text-right { text-align: right; }

        .footer {
            text-align: center;
            font-size: 10px;
            color: var(--footer-color);
            margin-top: 30px;
            padding-top: 10px;
            border-top: 1px solid var(--border-color);
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="logo">
            <img src="{{ public_path('image/huellitas-felices.png') }}" alt="Logo">
        </div>
        <h1>Reporte de Facturas</h1>
    </div>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Cliente</th>
                <th>Fecha</th>
                <th class="text-right">Subtotal (S/)</th>
                <th class="text-right">Impuesto (S/)</th>
                <th class="text-right">Total (S/)</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($invoices as $invoice)
                <tr>
                    <td class="text-center">#{{ $invoice->id }}</td>
                    <td>{{ $invoice->customer->nombres }} {{ $invoice->customer->apellidos }}</td>
                    <td>{{ \Carbon\Carbon::parse($invoice->fecha)->format('d/m/Y') }}</td>
                    <td class="text-right">{{ number_format($invoice->sub_total, 2) }}</td>
                    <td class="text-right">{{ number_format($invoice->impuesto, 2) }}</td>
                    <td class="text-right">{{ number_format($invoice->total_metodo_pago, 2) }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="text-center" style="padding: 20px;">
                        <em>No hay facturas registradas.</em>
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        Generado el {{ now()->format('d/m/Y H:i') }} | Sistema de Gestión Vida Saludable
    </div>
</body>
</html>

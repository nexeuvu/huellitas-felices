<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Reporte de Clientes</title>
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
        <h1>Reporte de Clientes</h1>
    </div>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Documento</th>
                <th>Nombre</th>
                <th>Apellido</th>
                <th>Dirección</th>
                <th>Teléfono</th>
                <th>Email</th>
                <th class="text-center">Fecha Registro</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($customers as $customer)
                <tr>
                    <td class="text-center">#{{ $customer->id }}</td>
                    <td>{{ $customer->tipo_documento }} - {{ $customer->dni }}</td>
                    <td>{{ $customer->nombres }}</td>
                    <td>{{ $customer->apellidos }}</td>
                    <td>{{ $customer->direccion ?? '—' }}</td>
                    <td>{{ $customer->telefono ?? '—' }}</td>
                    <td>{{ $customer->email ?? '—' }}</td>
                    <td class="text-center">{{ \Carbon\Carbon::parse($customer->fecha_registro)->format('d/m/Y') }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="8" class="text-center" style="padding: 20px;">
                        <em>No hay clientes registrados.</em>
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

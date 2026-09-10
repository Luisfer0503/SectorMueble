<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Factura CFDI 4.0 - Folio #{{ str_pad($pedido->id, 6, '0', STR_PAD_LEFT) }} | Sector Mueble</title>
    <style>
        body {
            font-family: 'Segoe UI', Arial, sans-serif;
            font-size: 12px;
            color: #1a1a1a;
            margin: 0;
            padding: 24px;
            background-color: #ffffff;
        }
        .header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            border-bottom: 3px solid #88674B;
            padding-bottom: 16px;
            margin-bottom: 20px;
        }
        .logo-title {
            font-size: 22px;
            font-weight: 800;
            color: #88674B;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        .sub-logo {
            font-size: 11px;
            color: #666;
            margin-top: 4px;
        }
        .factura-badge {
            text-align: right;
        }
        .factura-title {
            font-size: 18px;
            font-weight: 700;
            color: #4c6f4f;
        }
        .grid-2 {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 16px;
            margin-bottom: 20px;
        }
        .box {
            background-color: #faf8f5;
            border: 1px solid #e5dfd5;
            border-radius: 8px;
            padding: 12px;
        }
        .box-title {
            font-size: 11px;
            font-weight: 700;
            color: #88674B;
            text-transform: uppercase;
            border-bottom: 1px solid #e5dfd5;
            padding-bottom: 4px;
            margin-bottom: 8px;
        }
        .info-row {
            margin-bottom: 4px;
        }
        .info-label {
            font-weight: 600;
            color: #555;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        th {
            background-color: #4c6f4f;
            color: #ffffff;
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            padding: 8px 10px;
            text-align: left;
        }
        td {
            padding: 8px 10px;
            border-bottom: 1px solid #eee;
            font-size: 11px;
        }
        .totales-table {
            width: 280px;
            margin-left: auto;
            border: 1px solid #e5dfd5;
            border-radius: 8px;
            overflow: hidden;
        }
        .totales-table td {
            padding: 6px 12px;
        }
        .total-final {
            background-color: #88674B;
            color: #ffffff;
            font-weight: 800;
            font-size: 14px;
        }
        .sat-block {
            margin-top: 30px;
            border-top: 1px dashed #ccc;
            padding-top: 16px;
            display: flex;
            gap: 16px;
            align-items: center;
        }
        .qr-placeholder {
            width: 90px;
            height: 90px;
            background-color: #eee;
            border: 1px solid #ccc;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 9px;
            text-align: center;
            color: #666;
            flex-shrink: 0;
        }
        .sello-text {
            font-family: monospace;
            font-size: 9px;
            color: #555;
            word-break: break-all;
            line-height: 1.3;
        }
        @media print {
            .no-print { display: none !important; }
            body { padding: 0; }
        }
    </style>
</head>
<body>

    <!-- Botón Imprimir / Guardar en PDF (Oculto al imprimir) -->
    <div class="no-print" style="margin-bottom: 16px; text-align: right;">
        <button onclick="window.print()" style="background-color: #4c6f4f; color: white; border: none; padding: 8px 18px; border-radius: 6px; font-weight: bold; cursor: pointer;">
            🖨️ Imprimir / Guardar como PDF
        </button>
    </div>

    <!-- Header -->
    <div class="header">
        <div>
            <div class="logo-title">SECTOR MUEBLE</div>
            <div class="sub-logo">SECTOR MUEBLE S.A. DE C.V. | RFC: SME260101XXX</div>
            <div class="sub-logo">Régimen Fiscal: 601 - General de Ley Personas Morales</div>
            <div class="sub-logo">Lugar de Expedición: CP 72000 (Puebla, México)</div>
        </div>
        <div class="factura-badge">
            <div class="factura-title">COMPROBANTE FISCAL DIGITAL (CFDI 4.0)</div>
            <div style="font-weight: 700; font-size: 13px; margin-top: 4px;">Folio: SM-{{ str_pad($pedido->id, 6, '0', STR_PAD_LEFT) }}</div>
            <div style="font-size: 11px; color: #666;">Fecha: {{ $pedido->created_at->format('d/m/Y H:i:s') }}</div>
        </div>
    </div>

    <!-- Grid Fiscales -->
    <div class="grid-2">
        <!-- Emisor / Datos del Pedido -->
        <div class="box">
            <div class="box-title">Datos del Comprobante</div>
            <div class="info-row"><span class="info-label">Efecto de Comprobante:</span> I - Ingreso</div>
            <div class="info-row"><span class="info-label">Método de Pago:</span> PUE - Pago en una sola exhibición</div>
            <div class="info-row"><span class="info-label">Forma de Pago:</span> 04 - Tarjeta de Crédito / Débito</div>
            <div class="info-row"><span class="info-label">Moneda:</span> MXN (Pesos Mexicanos)</div>
        </div>

        <!-- Receptor / Cliente -->
        <div class="box">
            <div class="box-title">Receptor (Datos Fiscales del Cliente)</div>
            <div class="info-row"><span class="info-label">RFC:</span> {{ $pedido->rfc_receptor ?? 'XAXX010101000' }}</div>
            <div class="info-row"><span class="info-label">Nombre / Razón Social:</span> {{ $pedido->razon_social ?? $pedido->nombre_cliente }}</div>
            <div class="info-row"><span class="info-label">Régimen Fiscal:</span> {{ $pedido->regimen_fiscal ?? '616' }}</div>
            <div class="info-row"><span class="info-label">Uso de CFDI:</span> {{ $pedido->uso_cfdi ?? 'G03' }}</div>
            <div class="info-row"><span class="info-label">Código Postal Fiscal:</span> {{ $pedido->codigo_postal_fiscal ?? $pedido->codigo_postal }}</div>
        </div>
    </div>

    <!-- Tabla Conceptos -->
    <table>
        <thead>
            <tr>
                <th>Clave SAT</th>
                <th>Cant.</th>
                <th>Unidad</th>
                <th>Descripción</th>
                <th style="text-align: right;">P. Unitario</th>
                <th style="text-align: right;">Importe</th>
            </tr>
        </thead>
        <tbody>
            @php $subtotalCalculado = 0; @endphp
            @foreach($pedido->detalles as $det)
                @php
                    $imp = $det->precio * $det->cantidad;
                    $subtotalCalculado += $imp;
                @endphp
                <tr>
                    <td style="font-family: monospace;">56112100</td>
                    <td>{{ $det->cantidad }}</td>
                    <td>H87 (Pieza)</td>
                    <td><strong>{{ $det->nombre_producto }}</strong></td>
                    <td style="text-align: right; font-family: monospace;">$ {{ number_format($det->precio, 2, '.', ',') }}</td>
                    <td style="text-align: right; font-family: monospace; font-weight: 600;">$ {{ number_format($imp, 2, '.', ',') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <!-- Totales -->
    <table class="totales-table">
        <tr>
            <td class="info-label">Subtotal:</td>
            <td style="text-align: right; font-family: monospace;">$ {{ number_format($subtotalCalculado, 2, '.', ',') }}</td>
        </tr>
        @if($pedido->descuento > 0)
            <tr>
                <td class="info-label" style="color: #c53030;">Descuento:</td>
                <td style="text-align: right; font-family: monospace; color: #c53030;">-$ {{ number_format($pedido->descuento, 2, '.', ',') }}</td>
            </tr>
        @endif
        <tr>
            <td class="info-label">IVA (16% Incluido):</td>
            <td style="text-align: right; font-family: monospace;">$ {{ number_format($pedido->total * 0.16 / 1.16, 2, '.', ',') }}</td>
        </tr>
        <tr class="total-final">
            <td>TOTAL MXN:</td>
            <td style="text-align: right; font-family: monospace;">$ {{ number_format($pedido->total, 2, '.', ',') }}</td>
        </tr>
    </table>

    <!-- Timbre SAT -->
    <div class="sat-block">
        <div class="qr-placeholder">
            QR SAT<br>CFDI 4.0
        </div>
        <div>
            <div class="info-row"><span class="info-label">Folio Fiscal (UUID):</span> <span style="font-family: monospace; font-weight: 700;">{{ $pedido->factura_uuid ?? 'CFDI40-DEMO-0000-0000-000000000000' }}</span></div>
            <div class="info-row"><span class="info-label">No. Certificado SAT:</span> <span style="font-family: monospace;">00001000000504465028</span></div>
            <div class="info-row"><span class="info-label">Cadena Original del Timbre:</span></div>
            <div class="sello-text">||1.1|{{ $pedido->factura_uuid }}|{{ $pedido->created_at->toIso8601String() }}|SAT970701NN3|{{ md5($pedido->id . $pedido->total) }}||</div>
        </div>
    </div>

</body>
</html>

<!DOCTYPE html>
<html>
<head>
    <title>Ticket</title>
    <style>
        @page {
            margin: 5px;
            size: 80mm 297mm;
        }
        body {
            font-family: 'Courier New', Courier, monospace;
            font-size: 8pt;
            margin: 0;
            padding: 5px;
        }
        .header {
            text-align: center;
            margin-bottom: 10px;
        }
        .logo {
            max-width: 150px;
            margin: 0 auto;
        }
        .divider {
            border-top: 1px dashed #000;
            margin: 5px 0;
        }
        .text-center {
            text-align: center;
        }
        .text-right {
            text-align: right;
        }
        .productos {
            width: 100%;
            margin: 10px 0;
        }
        .productos td {
            padding: 2px 0;
        }
        .total {
            font-weight: bold;
            margin-top: 10px;
        }
    </style>
</head>
<body>
    <div class="header">
        <img class="logo" src="{{ asset("storage/images/$info->logo_empresa") }}" />
        <h3 style="margin: 5px 0">{{ $info->nombre_empresa }}</h3>
        <p style="margin: 2px 0">RFC: {{ $info->rfc }}</p>
        <p style="margin: 2px 0">{{ $info->calle }} {{ $info->num_ext }}</p>
        <p style="margin: 2px 0">{{ $info->colonia }}</p>
        <p style="margin: 2px 0">{{ $info->municipio }}, {{ $info->estado }}</p>
        <p style="margin: 2px 0">Tel: {{ $info->telefono }}</p>
    </div>

    <div class="divider"></div>

    <div>
        <p style="margin: 2px 0">Ticket #: {{ $data->serie . str_pad($data->folio, 5, 0, STR_PAD_LEFT) }}</p>
        <p style="margin: 2px 0">Fecha: {{ $data->fechaelab->format('d/m/Y H:i') }}</p>
        <p style="margin: 2px 0">Cliente: {{ $data->cliente->razon_social }}</p>
        <p style="margin: 2px 0">RFC: {{ $data->cliente->rfc }}</p>
        @if(!empty($data->forma_pago))
        <p style="margin: 2px 0">Forma de pago: {{ $data->forma_pago }}</p>
        @endif
    </div>

    <div class="divider"></div>

    <table class="productos">
        @foreach($data->productos as $item)
        <tr>
            <td colspan="2">{{ $item->descr_art }}</td>
        </tr>
        <tr>
            <td>{{ $item->cant }} {{ $item->cve_unidad }} x ${{ number_format($item->prec, 2) }}</td>
            <td class="text-right">${{ number_format($item->prec * $item->cant, 2) }}</td>
        </tr>
        @endforeach
    </table>

    <div class="divider"></div>

    <div class="total">
        <table width="100%">
            <tr>
                <td>Subtotal:</td>
                <td class="text-right">${{ number_format($subtotal ?? 0, 2) }}</td>
            </tr>
            @if(isset($descuentoTotal) && $descuentoTotal > 0)
            <tr>
                <td>Descuento:</td>
                <td class="text-right">${{ number_format($descuentoTotal, 2) }}</td>
            </tr>
            @endif
            @forelse($desglose ?? [] as $item)
            <tr>
                <td>
                    {{ $item['Impuesto'] }} 
                    @if($item['Factor'] == 'Tasa' && $item['TasaCuota'] != 'E' && $item['TasaCuota'] != 'Exento' && is_numeric($item['TasaCuota']))
                        ({{ number_format($item['TasaCuota'] * 100, 0) }}%)
                    @elseif($item['Factor'] == 'Cuota')
                        (Cuota)
                    @endif:
                </td>
                <td class="text-right">${{ number_format($item['TotalImporte'], 2) }}</td>
            </tr>
            @empty
            @endforelse
            <tr>
                <td><strong>Total:</strong></td>
                <td class="text-right"><strong>${{ number_format($data->importe, 2) }}</strong></td>
            </tr>
        </table>
    </div>

    <div class="divider"></div>

    @if(!empty($data->condicion))
    <div class="text-center">
        <p style="margin: 5px 0"><b>CONDICIONES</b></p>
        <p style="margin: 2px 0">{{ $data->condicion }}</p>
    </div>
    @endif

    @if(!empty($data->notas))
    <div class="text-center">
        <p style="margin: 5px 0"><b>NOTAS</b></p>
        <p style="margin: 2px 0">{{ $data->notas }}</p>
    </div>
    @endif

    <div class="text-center" style="margin-top: 10px">
        <p>*GRACIAS POR SU COMPRA*</p>
        <p>{{ $textoNumber }}</p>
        <p>Artículos: {{ $data->productos->sum('cant') }}</p>
    </div>
</body>
</html>

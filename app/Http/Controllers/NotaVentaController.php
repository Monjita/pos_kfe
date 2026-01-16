<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\NotaVenta;
use App\Models\ParamDatosEmpresa;
use App\Http\Traits\NumeroATextoTrait;
use PDF;

class NotaVentaController extends Controller
{
    use NumeroATextoTrait;
    //
    // public function __construct()
    // {
    //     $this->middleware(['auth']);
    // }

     /**
     * Calcula el desglose de impuestos desde los productos guardados
     */
    private function calcularDesgloseImpuestos($nota)
    {
        $totalesPorGrupo = [];
        
        foreach ($nota->productos as $item) {
            $producto = $item->producto;
            if (!$producto || !$producto->impuestos) {
                continue;
            }
            
            $precio = (float) $item->prec;
            $cantidad = (float) $item->cant;
            $descuento = (float) ($item->desc1 ?? 0);
            
            // Calcular base (sin formatear aún para mantener precisión)
            $base = $precio * $cantidad;
            if ($descuento != 0) {
                $base = $base - ($base * ($descuento / 100));
            }
            
            // Calcular IEPS
            $importeIeps = 0;
            $iepsFactor = null;
            $iepsTasaCuota = null;
            
            if ($producto->impuestos->tasa_ieps !== null && $producto->impuestos->factor_ieps === 'Tasa') {
                $importeIeps = $base * (float) $producto->impuestos->tasa_ieps;
                $iepsFactor = 'Tasa';
                $iepsTasaCuota = (float) $producto->impuestos->tasa_ieps;
            } elseif ($producto->impuestos->cuota_ieps !== null && $producto->impuestos->factor_ieps === 'Cuota') {
                $importeIeps = $cantidad * (float) $producto->impuestos->cuota_ieps;
                $iepsFactor = 'Cuota';
                $iepsTasaCuota = (float) $producto->impuestos->cuota_ieps;
            }
            
            // Calcular IVA
            $importeIva = 0;
            $tasaIva = null;
            if ($producto->impuestos->iva !== null && $producto->impuestos->iva !== 'Exento') {
                $tasaIva = (float) $producto->impuestos->iva;
                $importeIva = $base * $tasaIva;
            }
            
            // Agregar IEPS al desglose
            if ($importeIeps > 0) {
                $grupo = "IEPS_{$iepsFactor}_{$iepsTasaCuota}";
                if (!isset($totalesPorGrupo[$grupo])) {
                    $totalesPorGrupo[$grupo] = [
                        'Impuesto' => 'IEPS',
                        'Factor' => $iepsFactor,
                        'TasaCuota' => $iepsTasaCuota,
                        'TotalImporte' => 0,
                    ];
                }
                $totalesPorGrupo[$grupo]['TotalImporte'] += $importeIeps;
            }
            
            // Agregar IVA al desglose
            if ($importeIva > 0 && $tasaIva !== null) {
                $grupo = "IVA_Tasa_{$tasaIva}";
                if (!isset($totalesPorGrupo[$grupo])) {
                    $totalesPorGrupo[$grupo] = [
                        'Impuesto' => 'IVA',
                        'Factor' => 'Tasa',
                        'TasaCuota' => $tasaIva,
                        'TotalImporte' => 0,
                    ];
                }
                $totalesPorGrupo[$grupo]['TotalImporte'] += $importeIva;
            }
        }
        
        // Formatear totales
        foreach ($totalesPorGrupo as &$impuesto) {
            $impuesto['TotalImporte'] = number_format($impuesto['TotalImporte'], 2, '.', '');
        }
        
        return $totalesPorGrupo;
    }

    /**
     * Calcula el subtotal desde los productos
     */
    private function calcularSubtotal($nota)
    {
        $subtotal = 0;
        foreach ($nota->productos as $item) {
            $subtotal += $item->prec * $item->cant;
        }
        return number_format($subtotal, 2, '.', '');
    }

    /**
     * Calcula el descuento total desde los productos
     */
    private function calcularDescuentoTotal($nota)
    {
        $descuentoTotal = 0;
        foreach ($nota->productos as $item) {
            $precio = $item->prec;
            $cantidad = $item->cant;
            $descuento = $item->desc1 ?? 0;
            $descuentoTotal += ($precio * ($descuento / 100) * $cantidad);
        }
        return number_format($descuentoTotal, 2, '.', '');
    }

    public function ticket($id)
    {
        $nota = NotaVenta::findOrFail($id);
        
        // Calcular desglose de impuestos
        $desglose = $this->calcularDesgloseImpuestos($nota);
        $subtotal = $this->calcularSubtotal($nota);
        $descuentoTotal = $this->calcularDescuentoTotal($nota);
        
        return PDF::loadView('pdf.ticket', [
            'data' => $nota,
            'info' => ParamDatosEmpresa::first(),
            'textoNumber' => $this->NumLet($nota->importe),
            'desglose' => $desglose,
            'subtotal' => $subtotal,
            'descuentoTotal' => $descuentoTotal
        ])
            ->setPaper([0, 0, 226.77, 841.89], 'portrait') // 80mm = 226.77pt, 297mm = 841.89pt
            ->setWarnings(false)
            ->setOptions([
                'tempDir' => base_path(),
                'chroot'  => base_path(),
                'isRemoteEnabled' => true,
            ])
            ->stream($nota->id . '.pdf');
    }
}

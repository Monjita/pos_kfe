<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CotizacionProducto extends Model
{
    use HasFactory;

    protected $table = 'cotizacion_det';
    public $timestamps = false;

    protected $fillable = [
        'cotizacion_id',
        'cve_doc',
        'num_par',
        'cve_prod',
        'cant',
        'pxs',
        'prec',
        'cost',
        'impu1',
        'impu2',
        'impu3',
        'impu4',
        'impu5',
        'impu6',
        'impu7',
        'impu8',
        'imp1apla',
        'imp2apla',
        'imp3apla',
        'imp4apla',
        'imp5apla',
        'imp6apla',
        'imp7apla',
        'imp8apla',
        'totimp1',
        'totimp2',
        'totimp3',
        'totimp4',
        'totimp5',
        'totimp6',
        'totimp7',
        'totimp8',
        'desc1',
        'desc2',
        'desc3',
        'comi',
        'apar',
        'act_inv',
        'cve_alm',
        'polit_apli',
        'tip_cam',
        'uni_venta',
        'tipo_prod',
        'cve_obs',
        'reg_serie',
        'e_ltpd',
        'tipo_elem',
        'num_mov',
        'tot_partida',
        'imprimir',
        'man_ieps',
        'apl_man_imp',
        'cuota_ieps',
        'apl_man_ieps',
        'mto_porc',
        'mto_cuota',
        'cve_esq',
        'descr_art',
        'uuid',
        'version_sinc',
        'prec_neto',
        'id_relacion',
        'cve_prodserv',
        'cve_unidad',
        'ltpd'
    ];
    
    public function cotizacion()
    {
        return $this->belongsTo(Cotizacion::class);
    }

    public function producto()
    {
        return $this->hasOne(Producto::class, 'clave', 'cve_prod')->with('unidades', 'impuestos', 'stock', 'precios');
    }
}

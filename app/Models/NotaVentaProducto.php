<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class NotaVentaProducto extends Model
{
    use HasFactory;

    protected $table = 'nota_det';
    
    public $timestamps = false;

    protected $fillable = [
        'nota_id',
        'num_par',
        'cve_prod',
        'cve_alm',
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
        'polit_apli',
        'tip_cam',
        'uni_venta',
        'tipo_prod',
        'cve_obs',
        'reg_serie',
        'e_ltpd',
        'lote_venta',
        'tipo_elem',
        'tot_partida',
        'man_ieps',
        'apl_man_imp',
        'cuota_ieps',
        'apl_man_ieps',
        'mto_porc',
        'mto_cuota',
        'descr_art',
        'prec_neto',
        'id_relacion',
        'cve_prodserv',
        'cve_unidad'
    ];

    public function nota()
    {
        return $this->belongsTo('App\Models\NotaVenta', 'nota_id');
    }

    public function almacen()
    {
        return $this->belongsTo('App\Models\Almacen', 'cve_alm');
    }

    public function producto()
    {
        return $this->belongsTo('App\Models\Producto', 'cve_prod')->withTrashed()->with('unidades');
    }
    
    //En esta tabla relacionamos el enlace de lotes a la tabla intermedia donde contiene cantidad y id de lotes que corresponde
    public function enlaceLotes(){
        return $this->belongsTo('App\Models\EnlaceLotes','e_ltpd','e_ltpd')->with('lotes');
    }  

    public function unidades()
    {
        return $this->belongsTo('App\Models\Unidad', 'cve_unidad', 'clave_sat');
    }
}

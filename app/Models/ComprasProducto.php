<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ComprasProducto extends Model
{
    use HasFactory;

    protected $table = 'compras_det';

    public $timestamps = false;

    protected $fillable = [
        'compras_id',
        'num_par',
        'cve_prod',
        'cve_alm',
        'cant',
        'pxr',
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
        'descu',
        'act_inv',
        'tip_cam',
        'uni_venta',
        'tipo_elem',
        'tipo_prod',
        'cve_obs',
        'reg_serie',
        'e_ltpd',
        'factconv',
        'cost_dev',
        'mindirecto',
        'tot_partida',
        'man_ieps',
        'apl_man_imp',
        'cuota_ieps',
        'apl_man_ieps',
        'mto_porc',
        'mto_cuota',
        'cve_esq',
        'descr_art'
    ];

    public function producto()
    {
        return $this->belongsTo('App\Models\Producto', 'cve_prod', 'id')->withTrashed();
    }

    public function compras()
    {
        return $this->belongsTo('App\Models\Compras', 'compras', 'id');
    }

    public function enlaceLotes(){
        return $this->belongsTo('App\Models\EnlaceLotes','e_ltpd','e_ltpd')->with('lotes');
    }

    public function unidades()
    {
        return $this->hasOne('App\Models\Unidad', 'id', 'unidad');
    }

     public function precios()
    {
        return $this->hasMany('App\Models\ProductoPrecios','cve_prod');
    }

    public function compra()
    {
        return $this->belongsTo('App\Models\Compras', 'compras_id');
    }
}

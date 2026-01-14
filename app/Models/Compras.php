<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Compras extends Model
{
    use HasFactory;

    protected $table = 'compras';

    public $timestamps = false;

    protected $fillable = [
        'tip_doc',
        'cve_clpv',
        'status',
        'su_refer',
        'fecha_doc',
        'fecha_rec',
        'fecha_pag',
        'fecha_cancela',
        'can_tot',
        'imp_tot1',
        'imp_tot2',
        'imp_tot3',
        'imp_tot4',
        'imp_tot5',
        'imp_tot6',
        'imp_tot7',
        'imp_tot8',
        'des_tot',
        'des_fin',
        'tot_ind',
        'obs_cond',
        'cve_obs',
        'cve_alm',
        'act_cxp',
        'enlazado',
        'tip_doc_e',
        'num_moned',
        'tipcamb',
        'num_pagos',
        'fechaelab',
        'serie',
        'folio',
        'ctlpol',
        'escfd',
        'contado',
        'bloq',
        'des_fin_porc',
        'des_tot_porc',
        'importe',
        'tip_doc_ant',
        'doc_ant',
        'tip_doc_sig',
        'doc_sig',
        'formaenvio',
        'metododepago'
    ];

    public function proveedor(){
        return $this->belongsTo('App\Models\Proveedor','cve_clpv','id');
    }

    public function almacen(){
        return $this->belongsTo('App\Models\Almacen','cve_alm','id')->withTrashed();
    }

    public function productos()
    {
        return $this->hasMany('App\Models\ComprasProducto', 'compras_id');
    }

    public function unidades(){
        return $this->belongsTo('App\Models\Unidad', 'unidad', 'id');
    }
}

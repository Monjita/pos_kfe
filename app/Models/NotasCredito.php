<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class NotasCredito extends Model
{
    use HasFactory;

    protected $table = 'notacred';
    
    public $timestamps = false;

    protected $fillable = [
        'tip_doc',
        'cve_alm',
        'cve_cliente',
        'status',
        'dat_mostr',
        'cve_vend',
        'cve_pedi',
        'fecha_doc',
        'fecha_ent',
        'fecha_ven',
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
        'com_tot',
        'condicion',
        'cve_obs',
        'act_cxc',
        'enlazado',
        'tip_doc_e',
        'num_moned',
        'tipcamb',
        'num_pagos',
        'fechaelab',
        'primerpago',
        'autoriza',
        'serie',
        'folio',
        'dat_envio',
        'contado',
        'cve_bita',
        'bloq',
        'formaenvio',
        'des_fin_porc',
        'des_tot_porc',
        'importe',
        'com_tot_porc',
        'metododepago',
        'numctapago',
        'tip_doc_ant',
        'doc_ant',
        'tip_doc_sig',
        'doc_sig',
        'uuid',
        'formadepagosat',
        'uso_cfdi',
        'cve_alm_des',
        'tip_traslado',
        'tip_fac',
        'reg_fisc',
        'qrCode',
    ];

    protected $dates = ['fechaelab','fecha_doc','fecha_ent'];

    public function cliente(){
        return $this->belongsTo('App\Models\Cliente','cve_cliente','id');
    }

    public function almacen(){
        return $this->belongsTo('App\Models\Almacen','cve_alm','id');
    }
    
    public function productos()
    {
        return $this->hasMany('App\Models\NotasCreditoProducto', 'notacred_id');
    }

    public function agentes()
    {
        return $this->belongsTo('App\Models\User', 'cve_vend', 'id');
    }
}

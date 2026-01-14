<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Cotizacion extends Model
{
    use HasFactory;
    
    protected $table = 'cotizacion';
    
    public $timestamps = false;

    protected $fillable = [
        'tip_doc',
        'cve_doc',
        'cve_clpv',
        'status',
        'cve_pedi',
        'fecha_doc',
        'fecha_ent',
        'fecha_ven',
        'imp_tot1',
        'imp_tot2',
        'imp_tot3',
        'imp_tot4',
        'imp_tot5',
        'imp_tot6',
        'imp_tot7',
        'imp_tot8',
        'des_fin',
        'com_tot',
        'num_monedd',
        'tipcamb',
        'primerpago',
        'rfc',
        'autoriza',
        'folio',
        'serie',
        'autoanio',
        'escfd',
        'cve_alm',
        'act_cxc',
        'act_coi',
        'can_tot',
        'cve_vend',
        'fecha_cancela',
        'des_tot',
        'condicion',
        'notas',
        'num_pagos',
        'dat_envio',
        'contado',
        'dat_mostr',
        'cve_bita',
        'bloq',
        'fechaelab',
        'ctlpol',
        'cve_obs',
        'enlazado',
        'tip_doc_e',
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
        'version_sinc',
        'formadepagosat',
        'uso_cfdi',
        'num_alm_des',
        'tip_traslado',
        'tip_fac',
        'reg_fisc',
        'listaPrecio'
    ];

    public function detalles()
    {
        return $this->hasMany(CotizacionProducto::class)->with('producto');
    }
    public function cliente(){
        return $this->belongsTo('App\Models\Cliente','cve_clpv','id');
    }

    public function almacen(){
        return $this->belongsTo('App\Models\Almacen','cve_alm','id');
    }
    
    public function agentes()
    {
        return $this->belongsTo('App\Models\User', 'cve_vend', 'id');
    }
}

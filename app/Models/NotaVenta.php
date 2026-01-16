<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class NotaVenta extends Model
{
    use HasFactory;
    protected $table = 'nota';

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
        'notas',
        'comentarios',
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
        'listaPrecio'
    ];

    protected $dates = ['fechaelab','fecha_doc','fecha_ent'];

    protected $casts = [
        'fechaelab' => 'datetime',
    ];

    public function cliente(){
        return $this->belongsTo('App\Models\Cliente','cve_cliente','id');
    }

    public function vendedor()
    {
        return $this->hasOne(User::class, 'id','cve_vend')->withTrashed();
    }

    public function almacen(){
        return $this->belongsTo('App\Models\Almacen','cve_alm','id');
    }

    public function productos()
    {
        return $this->hasMany('App\Models\NotaVentaProducto', 'nota_id')->with('producto');
    }

    public function agentes()
    {
        return $this->belongsTo('App\Models\User', 'cve_vend', 'id');
    }

    public function getFormaPagoAttribute()
    {
        $referencia = $this->serie . str_pad($this->folio, 5, 0, STR_PAD_LEFT);
        $pago = \App\Models\CuentaXCobrarDet::where('refer', $referencia)
            ->where('signo', -1)
            ->whereNotNull('num_cpto')
            ->first();
        
        if ($pago && !empty($pago->num_cpto)) {
            $concepto = \App\Models\ConceptoC::where('num_cpto', $pago->num_cpto)->first();
            return $concepto ? $concepto->descr : null;
        }
        
        return null;
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Cliente extends Model
{
    use HasFactory;

    protected $table = 'cliente';
    
    public $timestamps = true;

    protected $dates = ['created_at','deleted_at','updated_at'];

    protected $fillable = [
        'razon_social','nombre_comercial','giro_comercial','rfc','regimen_fiscal','email','curp','telefono','calle', 'calle1', 'calle2', 'num_int','num_ext','colonia','ciudad','estado','codigo_postal','pais','forma_pago','uso_cfdi','status','credito_dias','credito_limite','descuento','cobranza_contacto','cobranza_email', 'agente_id','composicion',
    ];

    public function regimen()
    {
        return $this->hasOne(CfdiRegimenFiscal::class, 'clave', 'regimen_fiscal');
    }

    public function user()
    {
        return $this->hasOne(User::class, 'agente_id');
    }

    // public function agentes()
    // {
    //     return $this->belongsTo('App\Models\User', 'agente_id', 'id');
    // }

    // public function cuentasPendientes()
    // {
    //     return $this->hasMany(CuentaXCobrar::class, 'cve_clie')
    //         ->with('detalles','saldo');
    // }

}

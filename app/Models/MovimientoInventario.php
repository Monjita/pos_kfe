<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class MovimientoInventario extends Model
{
    use HasFactory;
    protected $table = 'mov_inve';

    protected $primaryKey = 'num_mov';

    public $timestamps = false;

    protected $dates = ['fecha_doc', 'fechaelab'];

    protected $fillable = [
        'cve_prod', 'almacen', 'num_mov', 'cve_cpto', 'fecha_docu', 'tipo_doc', 'refer', 'clave_clpv', 'vend', 'cant', 'cant_cost', 'precio', 'costo', 'afec_coi', 'cve_obs', 'reg_serie', 'uni_venta', 'e_ltpd', 'exist_g', 'existencia', 'tipo_prod', 'factor_con', 'fechaelab', 'ctlpol', 'cve_folio', 'signo', 'costeado', 'costo_prom_ini', 'costo_prom_fin', 'costo_prom_gral', 'desde_inve', 'mov_enlazado','agente'
    ];

    public function producto()
    {
        return $this->belongsTo('App\Models\Producto', 'cve_prod', 'id');
    }

    public function almacen()
    {
        return $this->belongsTo('App\Models\Almacen', 'almacen', 'id');
    }

    // public function almacenes()
    // {
    //     return $this->belongsTo('App\Models\Almacen', 'almacen', 'id');
    // }

    public function concepto()
    {
        return $this->belongsTo('App\Models\ConceptoMovimiento', 'cve_cpto', 'id');
    }

    public function cliente()
    {
        return $this->belongsTo('App\Models\Cliente', 'clave_clpv', 'id');
    }

    public function proveedor()
    {
        return $this->belongsTo('App\Models\Proveedor', 'clave_clpv', 'id');
    }

    public function enlaceLotes()
    {
        return $this->belongsTo('App\Models\EnlaceLotes', 'e_ltpd', 'e_ltpd');
    }

    public function nota()
    {
        return $this->belongsTo('App\Models\NotaVentaProducto', 'e_ltpd', 'e_ltpd');
    }

    public function agentes()
    {
        return $this->belongsTo('App\Models\User', 'agente', 'id');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ProductoLote extends Model
{
    use HasFactory;
    
    protected $table = 'ltpd';
    
    public $timestamps = false;

    protected $fillable = [
        'cve_alm','cve_prod','lote','pedimento','fchcaduc','fchaduana','fchultmov','nom_aduan','cantidad','cve_obs','ciudad','frontera','fec_prod_lt','gln','status','pedimentosat', 'costo', 'costo_origen', 'origen'
    ];

    protected $dates = ['fchcaduc','fchaduana','fchultmov','fec_prod_lt'];

    public function producto()
    {
        return $this->belongsTo('App\Models\Producto', 'cve_prod', 'id')->withTrashed();
    }
    
    public function almacen()
    {
        return $this->belongsTo('App\Models\Almacen', 'cve_alm', 'id')->withTrashed();
    }
}

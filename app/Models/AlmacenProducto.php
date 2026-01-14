<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AlmacenProducto extends Model
{
    use HasFactory;

    protected $table = 'alm_prod_det';
    
    public $timestamps = false;

    protected $fillable = [
        'cve_alm','cve_prod','exist', 'status','ctrl_alm', 'stock_min','stock_max',
    ];

    public function producto()
    {
        return $this->belongsTo('App\Models\Producto', 'cve_prod', 'id')->withTrashed();;
    }

    public function almacen()
    {
        return $this->belongsTo('App\Models\Almacen', 'cve_alm', 'id')->withTrashed();
    }
}

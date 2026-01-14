<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Almacen extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $table = 'almacen';
    public $timestamps = true;
    protected $fillable = ['nombre','clave','encargado','telefono','codigo_postal','calle','num_int','num_ext','colonia','ciudad','estado','pais'];

    public function productos()
    {
        return $this->hasMany('App\Models\AlmacenProducto', 'cve_alm', 'id')->where('status', 'A');
    }
}

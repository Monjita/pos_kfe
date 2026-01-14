<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Producto extends Model
{
    // use SearchableTrait;

    use HasFactory;

    use SoftDeletes;

    // protected $searchable = [
    //     /**
    //      * Columns and their priority in search results.
    //      * Columns with higher values are more important.
    //      * Columns with equal values have equal importance.
    //      *
    //      * @var array
    //      */
    //     'columns' => [
    //         'producto.nombre' => 100,
    //         'producto.clave' => 10,
    //     ],

    // ];

    protected $table = 'producto';

    public $timestamps = true;

    protected $dates = ['deleted_at','created_at','updated_at'];

    protected $fillable = [
        'clave','tipo','no_parte','costo','nombre','tipo_costeo','imagen','costo_prom','unidad','moneda','marca','categoria','linea','peso','largo','alto','ancho','clave_sat','objecto_impuesto','con_lote','con_serie','minimo','maximo','punto_reorden','usuario','precio','status','composicion','nombreComposicion', 'sugerencias'
    ];

    public function impuestos()
    {
        return $this->hasOne('App\Models\ProductoImpuesto', 'cve_prod', 'id');
    }

    // public function lineas()
    // {
    //     return $this->hasOne('App\Models\Linea', 'id', 'linea');
    // }

    // public function categorias()
    // {
    //     return $this->hasOne('App\Models\Categoria', 'id', 'categoria');
    // }

    // public function marcas()
    // {
    //     return $this->hasOne('App\Models\Marca', 'id', 'marca');
    // }

    public function unidades()
    {
        return $this->hasOne('App\Models\Unidad', 'id', 'unidad');
    }

    public function precios()
    {
        return $this->hasMany('App\Models\ProductoPrecios','cve_prod');
    }
    //Existencia total en todos sumando todos los almacenes disponibles
    public function stock(){
        return $this->hasMany('App\Models\AlmacenProducto', 'cve_prod');
    }

    public function lotes(){
        return $this->hasMany('App\Models\ProductoLote', 'cve_prod');
    }
}

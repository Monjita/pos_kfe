<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
// use App\Http\Traits\SearchableTrait;

class ListaPrecios extends Model
{
    use HasFactory;

    // use SearchableTrait;

    // protected $searchable = [
    //     /**
    //      * Columns and their priority in search results.
    //      * Columns with higher values are more important.
    //      * Columns with equal values have equal importance.
    //      *
    //      * @var array
    //      */
    //     'columns' => [
    //         'precios.id' => 100,
    //         'precios.descripcion' => 10,
    //     ],

    // ];
    
    protected $table = 'precios';
    
    public $timestamps = false;

    protected $fillable = [
        'descripcion','status','con_imp'
    ];
}

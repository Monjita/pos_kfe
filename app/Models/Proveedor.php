<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
// use App\Http\Traits\SearchableTrait;

class Proveedor extends Model
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
    //         'proveedores.nombre' => 10,
    //         'proveedores.rfc' => 10,
    //     ],

    // ];
    
    protected $table = 'proveedores';

    protected $fillable = [
        'status','rfc','nombre','calle','num_int','num_ext','colonia','cp','localidad','municipio','estado','telefono','pag_web','con_credito','dias_cred','lim_cred','saldo'
    ];

    // public function cuentasPendientes()
    // {
    //     return $this->hasMany(CuentaXPagar::class, 'cve_prov')
    //         ->with('detalles','saldo');
    // }
}

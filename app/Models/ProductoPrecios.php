<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ProductoPrecios extends Model
{
    use HasFactory;

    protected $table = 'precio_x_prod';

    public $timestamps = false;

    protected $fillable = [
        'precio','cve_prod','cve_precio', 'porcentaje_precio'
    ];
}

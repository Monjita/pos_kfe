<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ProductoImpuesto extends Model
{
    use HasFactory;
    
    protected $table = 'producto_impuesto';
    
    public $timestamps = false;

    protected $fillable = [
        'cve_prod','iva','tasa_ieps','factor_ieps','cuota_ieps'
    ];
}

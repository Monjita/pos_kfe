<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class EnlaceLotes extends Model
{
    use HasFactory;

    protected $table = 'enlace_ltpd';
    
    protected $primaryKey = 'e_ltpd';
    
    public $timestamps = false;

    protected $fillable = [
        'reg_ltpd','cantidad','pxrs','costo'
    ];

    public function lotes()
    {
        return $this->belongsTo('App\Models\ProductoLote', 'reg_ltpd', 'id');
    }
}

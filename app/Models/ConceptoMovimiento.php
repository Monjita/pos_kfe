<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ConceptoMovimiento extends Model
{
    use HasFactory;
    
    protected $table = 'conc_mov';
    
    public $timestamps = false;

    protected $fillable = [
        'descr','cpn','cuen_cont','tipo_mov','status','signo'
    ];
}

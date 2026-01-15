<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Unidad extends Model
{
    use HasFactory;

    protected $table = 'cat_unidad';

    public $timestamps = false;

    protected $fillable = ['clave_sat','descripcion','clave'];
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ParamDatosEmpresa extends Model
{
    use HasFactory;
    protected $table = 'param_datosemp';
    public $timestamps = false;
}

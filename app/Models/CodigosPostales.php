<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CodigosPostales extends Model
{
    use HasFactory;
    protected $table = 'localidades_cp';
    public $timestamps = true;
}

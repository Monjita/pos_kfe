<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ConceptoCuentaXPagar extends Model
{
    use HasFactory;
    protected $table = 'conp01';
    protected $primaryKey = 'num_cpto';
    public $timestamps = false;

    protected $fillable = [
        'num_cpto','descr','tipo','cuen_cont','con_refer','gen_cpto','autorizacion','signo','es_fma_pag','cve_bita','status','enlinea','dar_cambio','formadepagosat'
    ];
}

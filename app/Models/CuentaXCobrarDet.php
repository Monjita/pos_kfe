<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CuentaXCobrarDet extends Model
{
    use HasFactory;
    protected $table = 'paga_det01';
    
    protected $fillable = [
        'cve_prov',
        'refer',
        'id_mov',
        'num_cpto',
        'num_cargo',
        'cve_obs',
        'no_factura',
        'docto',
        'importe',
        'fecha_apli',
        'fecha_venc',
        'afec_coi',
        'strcvevend',
        'num_moned',
        'tcambio',
        'impmon_ext',
        'fechaelab',
        'ctlpol',
        'cve_folio',
        'tipo_mov',
        'cve_bita',
        'signo',
        'cve_aut',
        'usuario',
        'operacionpl',
        'ref_sist',
        'no_partida',
        'refbanco_origen',
        'refbanco_dest',
        'numctapago_origen',
        'numctapago_destino',
        'numcheque',
        'beneficiario',
        'uuid',
        'version_sinc',
        'id_operacion',
        'cve_doc_comppago',
        'usuariogl',
    ];

    protected $dates = ['fecha_apli','fecha_venc','fechaelab' ];

    public $timestamps = false;
   
    public function getDateFormat()
    {
        return 'Y-m-d H:i:s';
    }

    public function proveedor(){
        return $this->belongsTo(Proveedor::class, 'cve_prov');
    }

    public function concepto(){
        return $this->belongsTo(ConceptoCuentaXPagar::class, 'num_cpto','num_cpto');
    }

    public function cuenta()
    {
        return $this->belongsTo(CuentaXPagar::class,'refer','refer');
    }
}

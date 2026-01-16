<?php

use Livewire\Volt\Component;
use App\Models\Almacen;
use App\Models\Cliente;
use App\Models\Producto;
use App\Models\NotaVenta;
use App\Models\AlmacenProducto;
use App\Models\NotaVentaProducto;
use App\Models\ConceptoMovimiento;
use Illuminate\Support\Facades\DB;
use App\Models\MovimientoInventario;
use App\Models\ConceptoCuentaXCobrar;

new class extends Component {
    public $cliente;
    public $direccion;
    public $clienteDireccion;
    public $clienteId;
    public $productos = [];
    public $listaIndexProductos = [];

    public $almacen;
    public $vendedor;

    public $listaPrecio;
    public $condicion;
    public $pago;

    public $almacenes;
    public $listasPrecios = 1;
    public $descuento;
    public $total;
    public $subTotal;
    public $desgloce;
    public $pagos;
    public $proveedor;
    public $creditoCheck;
    public $notas;
    public $comentarios;
    public $dataDefault = true;

    protected $listeners = [
        'addPartida' => 'recibeArray',
        'reset' => 'resetComponente',
        'resetProductos' => 'resetProductos',
        'editarProducto' => 'editarProducto',
        'sendClienteSeleccionado' => 'sendClienteSeleccionado',
        'validarProductoSeleccionado' => 'validarProductoSeleccionado',
        'addDireccionVenta' => 'recibeDireccion',
        'cargarDirecciones' => 'cargarDirecciones',
        'sendDireccion' => 'sendDireccion',
    ];

    public function render(): mixed
    {
        $this->calcularSubTotal();
        $this->grupoImpuestos();
        $this->calcularTotal();

        $this->almacenes = Almacen::get();
        $this->listasPrecios = DB::table('precios')->where('status', 'A')->get();
        $this->pagos = ConceptoCuentaXCobrar::where('es_fma_pag', 'S')->get();
        $this->pago = 10;
         if($this->dataDefault){
            $cliente = Cliente::where('id', 1)->first();
            $this->cliente = $cliente ? $cliente->id : null;
            $almacen = $this->almacenes->first();
            $this->almacen = $almacen ? $almacen->id : null;
            $listaPrecio = DB::table('precios')->where('id', 1)->first();
            $this->listaPrecio = $listaPrecio ? $listaPrecio->id : null;
            $this->dataDefault = false;
        }

        return view('livewire.notaventa.create', [
            'clientes' => Cliente::orderBy('id', 'asc')->get(),
        ]);   
    }

    public function calcularSubTotal()
    {
        $this->subTotal = array_reduce($this->productos, function ($carry, $producto) {
            return $carry + ($producto['cantidad'] * $producto['precio']);
        }, 0);
    }

    public function calcularDescuentoTotal()
    {
        return array_reduce($this->productos, function ($carry, $producto) {
            return $carry + ($producto['precio'] * ($producto['descuento'] / 100) * $producto['cantidad']);
        }, 0);
    }

     public function calcularTotal()
    {
        $descuento = $this->calcularDescuentoTotal();
        $impuestos = array_reduce($this->desgloce, function ($carry, $impuesto) {
            return $carry + $impuesto['TotalImporte'];
        }, 0);

        $this->total = number_format(($this->subTotal - $descuento) + $impuestos, 2, '.', '');
    }

    public function grupoImpuestos()
    {
        $totalesPorGrupo = [];

        foreach ($this->productos as $producto) {
            foreach ($producto['impuestos'] as $impuesto) {
                $grupo = "{$impuesto['Impuesto']}_{$impuesto['Factor']}_{$impuesto['TasaCuota']}";

                if (!isset($totalesPorGrupo[$grupo])) {
                    $totalesPorGrupo[$grupo] = [
                        'Impuesto' => $impuesto['Impuesto'],
                        'Factor' => $impuesto['Factor'],
                        'TasaCuota' => $impuesto['TasaCuota'],
                        'TotalImporte' => 0,
                    ];
                }

                $totalesPorGrupo[$grupo]['TotalImporte'] += $impuesto['Importe'];
            }
        }
        $this->desgloce = $totalesPorGrupo;
        // Ahora $totalesPorGrupo contendrá los totales agrupados

    }

    public function abrirModal()
    {
        $this->dispatch('abrirProductosModal', ['listaPrecios' => $this->listasPrecios]);
    }

    public function recibeArray($array){
        if (array_key_exists($array['productoId'], $this->productos)) {
            $this->productos[$array['productoId']]['cantidad'] += $array['cantidad'];
        }
        else{
            $this->productos[$array['productoId']] = $array;
        }
        $this->productos[$array['productoId']]['importe'] = $array['importe'] * $this->productos[$array['productoId']]['cantidad'];
    }

    public function eliminarProducto($productoId){
        unset($this->productos[$productoId]);
    }
    

    public function save(){
        $this->validate([
            'cliente' => 'required',
        ], [
            'cliente.required' => 'El cliente es obligatorio.',
        ]);

        if (empty($this->productos)) {
            $this->dispatch('errorProductos', []);
            return;
        }
        try{
            DB::beginTransaction();
            $concepto = ConceptoMovimiento::find(51);
            $folio = str_pad(NotaVenta::max('folio') + 1, 5, '0', STR_PAD_LEFT);
            $folioMov = MovimientoInventario::orderByDesc('num_mov')->value('cve_folio') + 1;
            $cliente = Cliente::find($this->cliente);
            $nota = NotaVenta::create([
                'tip_doc' => 'V',
                'cve_alm' => $this->almacen,
                'cve_cliente' => $cliente->id,
                'status' => 'E',
                'cve_vend' => auth()->user()->id,
                'fecha_doc' => now(),
                'fecha_ent' => now(),
                'comentarios' => $this->comentarios,
                'fechaelab' => now(),
                'serie' => 'NT',
                'folio' => $folio,
                // 'dat_envio' => empty($this->direccion) ? 'SIN DIRECCION DE ENTREGA' : $this->direccion,
                'contado' => 'N',
                'des_tot_porc' => $this->descuento ?? 0,
                'importe' => $this->total,
                'metododepago' => null,
                'formadepagosat' => null,
                'uso_cfdi' => null,
                'reg_fisc' => $cliente->regimen_fiscal,
                'listaPrecio' => $this->listaPrecio
            ]);

            $cuenta_mov = DB::table('cuen_m01')->insert([
                'cve_clie' => $cliente->id,
                'refer' => $nota->serie.$nota->folio,
                'num_cpto' => '2',
                'num_cargo' => '1',
                'cve_obs' => null,
                'no_factura' => $nota->serie.$nota->folio,
                'docto' => $nota->serie.$nota->folio,
                'importe' => $nota->importe,
                'fecha_apli' => $nota->fecha_doc,
                'fecha_venc' => now(),
                'strcvevend' => null,
                'num_moned' => null,
                'tcambio' => null,
                'impmon_ext' => null,
                'fechaelab' => now(),
                'cve_folio' => null,
                'tipo_mov' => 'C',
                'cve_bita' => null,
                'signo' => 1,
                'cve_aut' => null,
                'usuario' => null,
                'entregada' => null,
                'fecha_entrega' => null,
                'status' => 'A',
            ]);
            if($this->pago){
                $nota->contado = 'S';
                $nota->save();
                DB::table('cuen_det01')->insert([
                    'cve_clie' => $cliente->id,
                    'refer' => $nota->serie.$nota->folio,
                    'id_mov' => '1',
                    'num_cpto' => $this->pago,
                    'num_cargo' => '1',
                    'cve_obs' => null,
                    'no_factura' =>$nota->serie.$nota->folio,
                    'docto' => $nota->serie.$nota->folio,
                    'importe' => $nota->importe,
                    'fecha_apli' => now(),
                    'fecha_venc' => null,
                    'afec_coi' => 'N',
                    'strcvevend' => null,
                    'num_moned' => null,
                    'tcambio' => null,
                    'impmon_ext' => null,
                    'fechaelab' => now(),
                    'cve_folio' => null,
                    'tipo_mov' => 'A',
                    'cve_bita' => null,
                    'signo' => -1,
                    'cve_aut' => null,
                    'usuario' => null,
                    'operacionpl' => null,
                    'ref_sist' => null,
                    'no_partida' => 1,
                    'refbanco_origen' => '',
                    'refbanco_dest' => null,
                    'numctapago_origen' => null,
                    'numctapago_destino' => null,
                    'numcheque' => null,
                    'beneficiario' => null,
                    'id_operacion' => null,
                    'cve_doc_comppago' => null,
                ]);
            }else{
                // Cliente::where('id', $this->cliente->id)->increment('saldo', $nota->importe);
            }
            // $lotesNuevos = [];
            foreach ($this->productos as $productoData) {
                $producto = Producto::find($productoData['productoId']);
                $notaDet = new NotaVentaProducto();
                $notaDet->nota_id = $nota->id;
                $notaDet->cve_prod = $productoData['productoId'];
                $notaDet->cve_alm = $this->almacen;
                $notaDet->cant = $productoData['cantidad'];
                $notaDet->desc1 = $productoData['descuento'];
                $notaDet->cost = $producto->costo;
                $notaDet->prec = $productoData['precio'];
                $notaDet->act_inv = 'S';
                // $notaDet->uni_venta = $productoData['unidad']['id'];
                $notaDet->tipo_elem = 'S';
                $notaDet->tipo_prod = $producto->tipo == 'Producto' ? 'P' : 'S';
                $notaDet->impu4 = $producto->impuestos->iva;
                // $notaDet->reg_serie  = 'P';
                // $notaDet->factconv = 'P';
                // $notaDet->factconv = 'P';
                $notaDet->descr_art = $productoData['descripcion'];
                $notaDet->cve_prodserv = $producto->clave_sat;
                // $notaDet->cve_unidad = $producto->unidades->clave_sat;

                $movimiento = new MovimientoInventario();
                $movimiento->cve_prod = $productoData['productoId'];
                $movimiento->almacen = $this->almacen;
                $movimiento->cve_cpto = $concepto->id;
                $movimiento->tipo_doc = 'V';
                $movimiento->fecha_docu = $nota->fecha_doc;
                $movimiento->refer = $nota->serie . $nota->folio;
                $movimiento->clave_clpv = $nota->cve_cliente;
                $movimiento->cant = $productoData['cantidad'];
                $movimiento->costo = $producto->costo;
                // $movimiento->uni_venta = $productoData['unidad']['id'];
              
                $movimiento->tipo_prod = $notaDet->tipo_prod;
                $movimiento->signo = $concepto->signo;
                $movimiento->desde_inve = 'S';
                $movimiento->agente = auth()->user()->id;
                $movimiento->cve_folio = $folioMov;
                $movimiento->fechaelab = now();
                $existenciaAlm = AlmacenProducto::where('cve_prod', $productoData['productoId'])
                    ->where('cve_alm', $this->almacen)
                    ->where('status', 'A')
                    ->first();

                if ($producto->tipo === 'Producto') {
                    $existenciaAlm->exist += ($productoData['cantidad'] * $concepto->signo);
                    $existenciaAlm->update();
                }
                $existenciaGeneral = AlmacenProducto::where('cve_prod', $productoData['productoId'])
                    ->where('status', 'A')
                    ->get();
                // Existencia general 
                $movimiento->exist_g = $existenciaGeneral->sum('exist');
                //Existencia se refiere
                $movimiento->existencia = $existenciaAlm->exist;
                $notaDet->save();
                $movimiento->save();
            }
            DB::commit();
            $this->dispatch('exito', []);
        }catch (\PDOException $e) {
            DB::rollBack();
            $this->dispatch('error', []);
            $this->addError('db', $e->getMessage());
        }

    }
}; ?>

<div>
    <div class="sm:flex flex-col w-full sm:gap-x-5">
        <div class="px-5">
            <div class="font-medium text-center text-base mr-auto">Información general</div>
        </div>

        <div class="px-2 flex flex-col sm:flex-row w-full sm:gap-x-5 mt-2">
            <div class="xl:w-1/3 xl:pr-1">
                <div>
                    <label for="cliente" class="block font-medium text-sm text-gray-700">
                        {{ __('Cliente') }}
                    </label>
                    <select id="cliente" wire:model="cliente" class="mt-1 block w-full text-sm border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                        <option value="">Seleccione una opción</option>
                        @foreach ($clientes as $cliente)
                            <option value="{{ $cliente->id }}">{{ $cliente->razon_social }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="xl:w-1/3 xl:pr-1">
                <div>
                    <label for="costeo" class="block font-medium text-sm text-gray-700">
                        {{ __('Almacen') }}
                    </label>
                    <select id="almacenes" wire:model="almacen" class="mt-1 block w-full text-sm border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                        <option value="">Seleccione una opción</option>
                        @foreach ($almacenes as $almacen)
                            <option value="{{ $almacen->id }}">{{ $almacen->nombre }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="xl:w-1/3 xl:pr-1">
                <div>
                    <label for="listasPrecios" class="block font-medium text-sm text-gray-700">
                        {{ __('Lista de precios') }}
                    </label>
                    <select id="listasPrecios" wire:model="listaPrecio" class="mt-1 block w-full text-sm border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                        <option value="">Seleccione una opción</option>
                        @forelse ($listasPrecios as $precio)
                            <option value="{{ $precio->id }}">{{ $precio->descripcion }}</option>
                        @empty
                        @endforelse
                    </select>
                </div>
            </div>
        </div>

        <div class="px-2 flex flex-col sm:flex-row w-full sm:gap-x-5 py-4">
            <button type="button" wire:click="abrirModal" class="text-sm bg-blue-700 hover:bg-blue-500 text-white font-bold py-2 px-4 rounded-lg">
                Añadir producto
            </button>
        </div>

        <div class="px-2 flex flex-col sm:flex-row w-full sm:gap-x-5">
            <div class="overflow-x-auto w-full">
                <table class="w-full text-sm border-2">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="px-3 py-2 text-center">Cantidad</th>
                            <th class="px-3 py-2 text-center">Clave</th>
                            <th class="px-3 py-2 text-center">Descripción</th>
                            <th class="px-3 py-2 text-center">Precio</th>
                            <th class="px-3 py-2 text-center">Importe</th>
                            <th class="px-3 py-2 text-center">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($productos as $producto)
                            <tr class="border-t hover:bg-gray-50" wire:key="producto-{{ $producto['productoId'] }}">
                                <td class="px-3 py-2 text-center">{{ $producto['cantidad'] }}</td>
                                <td class="px-3 py-2 text-center">{{ $producto['clave'] }}</td>
                                <td class="px-3 py-2 text-center">{{ $producto['descripcion'] }}</td>
                                <td class="px-3 py-2 text-center">{{ $producto['precio'] }}</td>
                                <td class="px-3 py-2 text-center">{{ $producto['importe'] }}</td>
                                <td class="px-3 py-2 text-center">
                                    <button class="px-2 py-1 bg-red-500 hover:bg-red-400 text-white rounded text-xs" wire:click="eliminarProducto({{ $producto['productoId'] }})">
                                        Eliminar
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center px-6 py-4 whitespace-nowrap text-base text-red-500">
                                    <i class="fa-solid fa-circle-exclamation fa-lg"></i>
                                    ¡No existen productos agregados!
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="flex justify-center w-full mt-5 gap-x-5">
        <div class="flex flex-col lg:flex-row w-full space-x-0 lg:space-x-4">
            <div class="flex flex-col lg:w-1/2 box shadow-xl w-full rounded-xl p-5 xl:w-4/5 mb-5">
                {{-- Comentarios --}}
                <div class="col-span-12">
                    <label for="message"
                        class="block font-medium text-sm text-gray-700">Comentarios</label>
                    <textarea id="message" rows="4" wire:model='comentarios'
                        class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                        placeholder="Visible en la impresión del documento"></textarea>
                </div>
            </div>
            <div class="flex flex-col lg:w-1/4 box shadow-xl w-full rounded-xl xl:w-4/5 mb-5">
                <div class="p-5">
                    <div class="col-span-12">
                        {{-- Pagos --}}
                        <label for="pagos" class="block font-medium text-sm text-gray-700">
                            Forma de pago
                        </label>
                        <select id="pagos" wire:model='pago' class="mt-1 block w-full text-sm border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" @if($creditoCheck) disabled @endif>
                            {{-- <option value=""></option> --}}
                            @forelse ($pagos as $item)
                                <option value="{{ $item->num_cpto }}">{{ $item->descr }}</option>
                            @empty
                            @endforelse
                        </select>
                    </div>
                </div>
            </div>
            <div class="flex flex-col lg:w-1/4 box shadow-xl w-full rounded-xl p-4 bg-gray-300 xl:w-4/5 mb-5">
                {{-- Desgloce de Impuestos y Totales --}}
                <table class="table">
                    <tr class="text-left">
                        <th>Subtotal</th>
                        <td align="right">{{ number_format($subTotal,2,'.',',') }}</td>
                    </tr>
                    <tr class="text-left">
                        <th>Descuento</th>
                        <td align="right">{{ number_format($this->calcularDescuentoTotal(),2,'.',',') }}</td>
                    </tr>
                    @forelse ($desgloce as $item )
                        <tr class="text-left">
                            <th>{{ $item['Impuesto'] }} ({{ $item['TasaCuota'] == 'E' ? 0 : $item['TasaCuota'] * 100 }} %)</th>
                            <td align="right">{{ number_format($item['TotalImporte'],2,'.',',') }}</td>
                        </tr>
                    @empty

                    @endforelse
                    <tr class="text-left">
                        <th>Total</th>
                        <td align="right">{{ number_format($total,2,'.',',') }}</td>
                    </tr>
                </table>
            </div>
        </div>
    </div>

    <div class="flex justify-center mt-5 gap-x-5">
        <a href="{{ route('ventas.index') }}" type="button" class=" text-sm bg-red-500 hover:bg-red-400 text-white font-bold py-2 px-4 rounded-lg" wire:navigate>
            <i class="fa-regular fa-circle-xmark mr-1"></i>
            Cancelar
        </a>

        <button type="button" wire:click="save" class=" text-sm bg-blue-700 hover:bg-blue-500 text-white font-bold py-2 px-4 rounded-lg">
            <i class="fa-regular fa-floppy-disk mr-1"></i>
            Guardar
        </button>
    </div>

    <livewire:notaventa.modalproductos />
</div>


@push('js')
    <script>
        window.addEventListener('exito', event => {
                Swal.fire({
                    title: 'Guardado con exito',
                    text: '',
                    icon: 'success',
                    confirmButtonColor: '#2563eb'
                }).then(() => {
                    window.location.href = "{{ route('ventas.index') }}";
                })
                
        })

        window.addEventListener('errorProductos', event => {
            Swal.fire({
                title: 'No existen productos seleccionados',
                text: '',
                icon: 'warning',
                confirmButtonColor: '#2563eb'
            })
        })

        window.addEventListener('error', event => {
            Swal.fire({
                title: 'Error al guardar',
                text: '',
                icon: 'warning',
                confirmButtonColor: '#2563eb'
            })
        })
    </script>
@endpush

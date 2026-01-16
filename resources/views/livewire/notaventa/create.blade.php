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

    public function save(){
        $this->validate([
            'cliente' => 'required',
        ], [
            'cliente.required' => 'El cliente es obligatorio.',
        ]);

        try{
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

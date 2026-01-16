<?php

use Livewire\Volt\Component;
use Livewire\WithPagination;
use App\Models\Producto;

new class extends Component {
     use WithPagination;

    protected $paginationTheme = 'tailwind';

    public bool $open = false;
    public $listaIndexProductos = [];
    public $cantidad = 0;
    public $precio = 0.00;
    public $descuento = 0.00;
    public $impuestos;
    public $importe = 0.00;
    public $impuestosArray = [];

    protected $listeners = ['abrirProductosModal'];

    public function render(): mixed
    {
        return view('livewire.notaventa.modalproductos', [
            'productos' => Producto::where('status', 'A')->paginate(5),
        ]);
    }

    public function abrirProductosModal($listaPrecios)
    {
        $this->resetPage(); // importante
        $this->open = true;
        $this->listaPrecios = $listaPrecios;
        // dd($this->listaPrecios);
    }

    public function cerrar()
    {
        $this->open = false;
    }

    public function productoSeleccionado($producto_id){
        // $this->resetPage();
        $producto = Producto::with('stock', 'unidades')->where('id', $producto_id)->first();
        // $index = $producto->id;
        $this->open = false;
        $this->descuento = 0.00;
        $this->cantidad = 1;
        $this->impuestos = $producto->impuestos;
        $this->precio = $producto->precios->where('cve_precio', 1)->pluck('precio')->first();
        // $this->precio = $producto->precios->where('cve_precio',$this->listaPrecio)->pluck('precio')->first();
        $this->calcularImpuestos();
        $productoData = [
            'productoId' => $producto->id,
            'precio' => $this->precio,
            'precioBase' => $this->precio,
            'importe' => $this->importe,
            'descuento' => $this->descuento,
            'cantidad' => $this->cantidad,
            // 'almacen' => $this->almacen,
            // 'unidad' => $this->unidadProducto,
            'descripcion' => $producto->nombre,
            'clave' => $producto->clave,
            'impuestos' => $this->impuestosArray,
            'indexProducto' => $producto->id,
            // 'editar' => $this->editar,
        ];
        $this->dispatch('addPartida', $productoData);
    }

    public function calcularImpuestos(){

        $this->validate([
            'cantidad' => 'required|min:1',
            'precio' => 'required'
        ]);

        $base = $this->formatNumber($this->precio * $this->cantidad);
        if($this->descuento != 0){
            $base = $base - ($base *($this->descuento/100));
        }
        $this->importe = $base;
        $importeIeps = 0;

        if ($this->impuestos->tasa_ieps !== null && $this->impuestos->factor_ieps === 'Tasa') {
            $importeIeps = $this->formatNumber($base * $this->impuestos->tasa_ieps);
        } elseif ($this->impuestos->cuota_ieps !== null && $this->impuestos->factor_ieps === 'Cuota') {
            $importeIeps = $this->formatNumber($this->cantidad * $this->impuestos->cuota_ieps);
        }

        $baseIva = $base;
        $importeIva = 0;

        if ($this->impuestos->iva !== 'Exento') {
            $importeIva = $this->formatNumber($baseIva * (float) $this->impuestos->iva);
        }

        // $this->precioBaseImp = $this->formatNumber(($base + $importeIeps + $importeIva) / $this->cantidad);

        $this->impuestosArray = [];

        // Verificar y agregar IEPS (Tasa o Cuota)
        if ($this->impuestos->tasa_ieps !== null || $this->impuestos->cuota_ieps !== null) {
            $iepsFactor = $this->impuestos->factor_ieps;
            $iepsValue = ($iepsFactor === 'Tasa') ? $this->impuestos->tasa_ieps : $this->impuestos->cuota_ieps;

            $this->impuestosArray[] = [
                'Base' => $base,
                'Impuesto' => 'IEPS',
                'Factor' => $iepsFactor,
                'TasaCuota' => $iepsValue,
                'Importe' => $importeIeps,
            ];
        }

        // Verificar y agregar IVA (Tasa)
        if ($this->impuestos->iva !== null) {
            $this->impuestosArray[] = [
                'Base' => $baseIva,
                'Impuesto' => 'IVA',
                'Factor' => 'Tasa',
                'TasaCuota' => $this->impuestos->iva,
                'Importe' => $importeIva,
            ];
        }
    }

    function formatNumber($number, $decimals = 2) {
        return number_format((float) $number, $decimals, '.', '');
    }

}; ?>

<div>
@if($open)
    <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
        <div class="bg-white rounded-lg shadow-lg w-11/12 md:w-3/4 lg:w-2/3 max-h-96 overflow-y-auto">
            <div class="flex justify-between items-center p-4 border-b">
                <h2 class="text-lg font-semibold">Seleccionar producto</h2>
                <button wire:click="cerrar" class="text-gray-600 hover:text-gray-900">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>

            <div class="p-4">
                <div class="overflow-x-auto">
                    <table class="w-full text-sm border">
                        <thead class="bg-gray-100">
                            <tr>
                                <th class="px-3 py-2 text-center">Descripcion</th>
                                <th class="px-3 py-2 text-center">Existencia</th>
                                <th class="px-3 py-2 text-center">Precio</th>
                                <th class="px-3 py-2 text-center">Acción</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($productos as $producto)
                                <tr class="border-t hover:bg-gray-50"  wire:key="producto-{{ $producto->id }}">
                                    <td class="w-40 py-2 px-6 whitespace-nowrap text-sm text-gray-900">
                                        <div class="flex">
                                            <div class="w-auto">
                                                <div class="mr-2 mt-1 h-10 w-10 flex justify-center items-center">
                                                    @if ($producto->imagen && file_exists(public_path("storage/images/$producto->imagen")))
                                                        <div class="flex justify-center items-center w-full h-full">
                                                            <img alt="{{ $producto->nombre }}" 
                                                                class="w-10 h-10 rounded-full border-2 border-blue-500 object-cover transition-transform duration-300 hover:scale-110" 
                                                                src="{{ asset("storage/images/$producto->imagen") }}">
                                                        </div>
                                                    @else
                                                        <div class="flex justify-center items-center w-10 h-10 rounded-full border-2 border-blue-500 bg-gray-100 text-blue-500">
                                                            <i class="fa-solid fa-exclamation"></i>
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="max-w-xs">
                                                <span class="text-xs text-gray-500">{{ $producto->clave }}</span>
                                                <div class="whitespace-nowrap overflow-ellipsis font-medium overflow-hidden">{{ $producto->nombre }}</div>
                                            </div>

                                        </div>
                                    </td>
                                    <td class="px-3 py-2 text-center">
                                        {{ $producto->stock->first()->exist ?? 0 }}
                                    </td>
                                    <td class="px-3 py-2 text-center">
                                        $ {{ number_format($producto->precios->first()->precio,2,'.',',') }}
                                    </td>
                                    <td class="px-3 py-2 text-center">
                                        <button
                                            wire:click="productoSeleccionado({{ $producto->id }})"
                                            class="px-2 py-1 bg-blue-600 text-white rounded text-xs hover:bg-blue-700">
                                            Elegir
                                        </button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-3 py-2 text-center">No hay productos disponibles.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="mt-3 p-4 border-t">
                {{ $productos->links() }}
            </div>

            <div class="flex justify-end p-4 border-t">
                <button
                    wire:click="cerrar"
                    class="px-4 py-2 bg-gray-500 text-white rounded hover:bg-gray-600">
                    Cerrar
                </button>
            </div>
        </div>
    </div>
@endif

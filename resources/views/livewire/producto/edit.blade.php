<?php

use Livewire\Volt\Component;
use Illuminate\Support\Facades\Auth;
use App\Models\Producto;
use Livewire\WithFileUploads;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;
use Illuminate\Support\Facades\DB;
use App\Models\ListaPrecios;
use App\Models\ProductoPrecios;
use App\Models\ProductoImpuesto;
use App\Models\Almacen;
use App\Models\AlmacenProducto;

new class extends Component {
    use WithFileUploads;
    public ?TemporaryUploadedFile $image = null;
    public $imagenProducto = null;
    public Producto $producto;
    public string $clave = '';
    public string $nombre = '';
    public string $marca = '';
    public string $linea = '';
    public string $categoria = '';
    public $unidad = 1;
    public $imp_iva = '0.160000';
    public $imp_ieps = '';

    public $tasa_iva = '0.160000';
    public $factor_ieps = 'Tasa';
    public $tasa_ieps = null;
    public $cuota_ieps = null;
    public $costeo = 'U';

    public $precios = [];
    public $stock = 0;
    public $porcentajePrecios = [];
    public $costoCompra = null;

    public function mount(int $id): void
    {
        $this->producto = $producto = Producto::findOrFail($id);
        $this->clave = $producto->clave;
        $this->nombre = $producto->nombre;
        $this->marca = $producto->marca;
        $this->linea = $producto->linea;
        $this->categoria = $producto->categoria;
        $this->imagenProducto = $producto->imagen;
        $this->costeo = $producto->tipo_costeo;
        $almacen = Almacen::find(1); //Almacen principal
        $almacenProducto = AlmacenProducto::where('cve_alm', $almacen->id)
                                ->where('cve_prod', $producto->id)
                                ->first();
        $this->stock = $almacenProducto ? $almacenProducto->exist : 0;

        foreach ($producto->precios as $item) {
            $this->porcentajePrecios[$item->cve_precio] = $item->porcentaje_precio;
            $this->precios[$item->cve_precio] = $item->precio;
        }
    }

      public function cambioPrecio($lista_id, $porcentaje){
        $valor = isset($porcentaje) && is_numeric($porcentaje) ? floatval($porcentaje) : 0;
        if($valor >= 0 && $porcentaje <= 100){
            $this->porcentajePrecios[$lista_id] = $valor;
            $this->precios[$lista_id] = number_format((float)($this->costoCompra + ($this->costoCompra * ($valor / 100))), 2, '.', '');
        }
        else{
            $valor = 0;
            $this->porcentajePrecios[$lista_id] = $valor;
            $this->precios[$lista_id] = number_format((float)($this->costoCompra + ($this->costoCompra * ($valor / 100))), 2, '.', '');
        }
    }

    public function save(){
        $this->validate([
                'clave' => 'required|regex:/^[a-zA-Z0-9\-\/]+$/|unique:producto,clave,' . $this->producto->id . ',id,deleted_at,NULL',
                'nombre' => 'required|string|max:255',
                'precios.*' => 'required|numeric',
             ],[
                'clave.required' => 'La clave es obligatoria.',
                'clave.unique' => 'La clave ya existe.',
                'clave.regex' => 'La clave solo puede contener letras, números, guiones y diagonales.',
                'nombre.required' => 'El nombre es obligatorio.',
                'precios.*.required' => 'El precio es obligatorio.',
                'precios.*.numeric' => 'El precio debe ser un número.',
            ]);
        
        try{
            if ($this->image) {
                $nombreFoto = uniqid() . '.' . $this->image->getClientOriginalExtension();
                $this->image->storeAs('images', $nombreFoto, 'public');
            }
            
            DB::beginTransaction();

            $this->producto->update([
                'clave' => $this->clave,
                'nombre' => $this->nombre,
                'categoria' => $this->categoria,
                'linea' => $this->linea,
                'marca' => $this->marca,
                'tipo_costeo' => $this->costeo,
                'imagen' => isset($nombreFoto) ? $nombreFoto : $this->imagenProducto,
            ]);

            $this->producto->impuestos->update([
                'iva' => $this->tasa_iva,
                'factor_ieps' => $this->factor_ieps,
                'tasa_ieps' => $this->factor_ieps == 'Tasa' ? $this->tasa_ieps : NULL,
                'cuota_ieps' => $this->factor_ieps == 'Cuota' ? $this->cuota_ieps : NULL,
            ]);

            foreach ($this->precios as $key => $value) {
                $this->producto->precios()
                ->where('cve_precio', $key)
                ->where('cve_prod', $this->producto->id)
                ->update(['precio' => $value,
                            'porcentaje_precio' => $this->porcentajePrecios[$key] ?? 0,
                        ]);
            }
            $almacen = Almacen::find(1); //Almacen principal
            $almacenProducto = AlmacenProducto::where('cve_alm', $almacen->id)
                                    ->where('cve_prod', $this->producto->id)
                                    ->first();

            $almacenProducto->update([
                'exist' => $this->stock,
            ]);

            DB::commit();
            // $this->redirect(route('inventario.index', absolute: false), navigate: true);
            $this->dispatch('exito', []);
        }catch (\PDOException $e) {
            DB::rollBack();
            $this->dispatch('error', []);
            $this->addError('db', $e->getMessage());

        }
    }

    public function render(): mixed
    {
        return view('livewire.producto.edit', [
            'lista_precios' => ListaPrecios::where('status','A')->get(),
        ]);
    }
}; ?>


<div>
    <div class="sm:flex sm:flex-row w-full sm:gap-x-5">

        <div class="w-full sm:w-2/3 overflow-y-auto">
            <div class="px-5">
                <div class="font-medium text-center text-base mr-auto">Información general</div>
            </div>

            <div class="px-2 flex flex-col sm:flex-row w-full sm:gap-x-5 mt-2">
                <div class="w-full">
                    <x-input-label for="clave" :value="__('Clave')" />
                    <x-text-input wire:model="clave" id="clave" name="clave" type="text" class="mt-1 block w-full" required autofocus autocomplete="clave" />
                    <x-input-error class="mt-2" :messages="$errors->get('clave')" />
                </div>

                <div class="w-full mt-2 sm:mt-0">
                    <x-input-label for="nombre" :value="__('Nombre')" />
                    <x-text-input wire:model="nombre" id="nombre" name="nombre" type="text" class="mt-1 block w-full" required autocomplete="nombre" />
                    <x-input-error class="mt-2" :messages="$errors->get('nombre')" />
                </div>
            </div>

            <div class="px-2 flex flex-col sm:flex-row w-full sm:gap-x-5 mt-5">
                <div class="w-full">
                    <x-input-label for="marca" :value="__('Marca')" />
                    <x-text-input wire:model="marca" id="marca" name="marca" type="text" class="mt-1 block w-full" required  autocomplete="marca" />
                    <x-input-error class="mt-2" :messages="$errors->get('marca')" />
                </div>

                <div class="w-full mt-2 sm:mt-0">
                    <x-input-label for="linea" :value="__('Linea')" />
                    <x-text-input wire:model="linea" id="linea" name="linea" type="text" class="mt-1 block w-full" required autocomplete="linea" />
                    <x-input-error class="mt-2" :messages="$errors->get('linea')" />
                </div>

                <div class="w-full mt-2 sm:mt-0">
                    <x-input-label for="categoria" :value="__('Categoria')" />
                    <x-text-input wire:model="categoria" id="categoria" name="categoria" type="text" class="mt-1 block w-full" required autocomplete="categoria" />
                    <x-input-error class="mt-2" :messages="$errors->get('categoria')" />
                </div>
            </div>

            <div class="px-5 pt-3 pb-2">
                <div class="font-medium text-center text-base mr-auto">Ventas</div>
            </div>

            <div class="px-2 flex flex-col sm:flex-row w-full sm:gap-x-5">
                <div class="xl:w-1/2 xl:pr-1">
                    <div>
                        <label for="costeo" class="block font-medium text-sm text-gray-700">
                            Costeo
                        </label>
                        <select id="costeo" wire:model.defer="costeo" class="mt-1 block w-full text-sm border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                            <option value="">Seleccione una opción</option>
                            <option value="U">Último Costo</option>
                            <option value="P">Costo Promedio</option>
                            <option value="A">Costo Más Alto</option>
                        </select>
                    </div>
                    <div class="mt-2">
                        <label for="iva" class="block font-medium text-sm text-gray-700">Tasa IVA%</label>
                        <select wire:model.defer='tasa_iva' id="iva" class="mt-1 block w-full text-sm border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                            <option value="0.000000">0%</option>
                            <option value="0.080000">8%</option>
                            <option value="0.160000">16%</option>
                            <option value="E">Exento</option>
                        </select>
                    </div>
                    <div class="w-full mt-2">
                        <x-input-label for="stock" :value="__('Stock')" />
                        <x-text-input wire:model="stock" id="stock" name="stock" type="text" class="mt-1 block w-full" required autocomplete="stock" />
                        <x-input-error class="mt-2" :messages="$errors->get('stock')" />
                    </div>
                </div>
                <div class="xl:w-1/2 xl:pl-1 mt-4 xl:mt-0">
                    <div class="flex flex-col xl:flex-row justify-center">
                        <div class="relative overflow-x-auto">
                            <table class="w-full text-left text-sm text-gray-500">
                                <thead class="text-center text-gray-900">
                                    <tr>
                                        <th class="{{ $costoCompra ? 'w-1/3' : 'w-1/2' }} px-2 py-1">Lista</th>
                                        <th class="{{ $costoCompra ? 'w-1/3 px-2 py-1' : 'hidden' }}">Fijar porcentaje</th>
                                        <th class="{{ $costoCompra ? 'w-1/3 px-2 py-1' : 'w-1/2 px-2 py-1' }}">Precio</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($lista_precios as $item)
                                        <tr class="border-b border-gray-200">
                                            <th scope="row" class=" whitespace-nowrap px-2 py-1 font-medium text-gray-900">
                                                {{ $item->descripcion }}
                                            </th>

                                            @if($costoCompra)
                                                <td class="px-2 py-1">
                                                    <input type="number" min="0" max="100"
                                                        id="porcentajePrecios_{{ $item->id }}"
                                                        wire:model="porcentajePrecios.{{ $item->id }}"
                                                        wire:change="cambioPrecio({{ $item->id }}, $event.target.value)"
                                                        class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm
                                                        @error("porcentajePrecios.{$item->id}") border-red-500 @enderror">

                                                    @error("porcentajePrecios.{$item->id}")
                                                        <div class="text-red-500 text-xs mt-1">{{ $message }}</div>
                                                    @enderror
                                                </td>

                                                <td class="px-2 py-1">
                                                    <input type="number"
                                                        id="precio_{{ $item->id }}"
                                                        wire:model="precios.{{ $item->id }}"
                                                        disabled
                                                        class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm
                                                        @error("precios.{$item->id}") border-red-500 @enderror">

                                                    @error("precios.{$item->id}")
                                                        <div class="text-red-500 text-xs mt-1">{{ $message }}</div>
                                                    @enderror
                                                </td>
                                            @else
                                                <td class="px-2 py-1">
                                                    <input type="text"
                                                        id="precio_{{ $item->id }}"
                                                        wire:model="precios.{{ $item->id }}"
                                                        class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm
                                                        @error("precios.{$item->id}") border-red-500 @enderror">

                                                    @error("precios.{$item->id}")
                                                        <div class="text-red-500 text-xs mt-1">{{ $message }}</div>
                                                    @enderror
                                                </td>
                                            @endif
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                    </div>
                </div>

            </div>

        </div>

        <div class="flex flex-col justify-start sm:w-1/3 mt-4 sm:mt-0 align-top">
            <div class="px-5">
                <div class="font-medium text-center text-base mr-auto">Imagen del producto</div>
            </div>

            @if($image || $imagenProducto)
                <div class="flex justify-center w-full my-2 mr-5">
                    <div class="flex px-5 items-center">
                        <div class="border-2 border-dashed shadow-sm border-slate-300 rounded-md py-5 px-10">
                            <div class="relative w-32 h-32 flex overflow-hidden">
                                <img alt="Foto de perfil" src="{{ $image != null ? $image->temporaryUrl() : asset('storage/images/' . $imagenProducto) }}" class="w-full h-full object-cover rounded-full border-2 border-blue-500">
                            </div>
                            <div class="mx-auto relative mt-5 text-center">
                                <label class="inline-flex items-center cursor-pointer text-xs font-bold bg-blue-700 hover:bg-blue-500 text-white py-2 px-3 rounded-lg">
                                    <span class="mx-2" wire:loading wire:target="image">
                                        <i class="fas fa-spinner fa-pulse"></i>
                                    </span>
                                    Cargar imagen
                                    <input type="file" class="hidden" wire:model="image">
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
            {{-- @elseif ($imagenProducto) --}}

            @else
                <div class="flex justify-center w-full my-2 mr-5">
                    <div class="flex px-5 items-center">
                        <div class="border-2 border-dashed shadow-sm border-slate-300 rounded-md py-5 px-10">
                            <div class="relative w-32 h-32 flex overflow-hidden">
                                <i class="fa-regular fa-camera text-gray-400 fa-7x mt-2"></i>
                            </div>
                            <div class="mx-auto relative mt-5 text-center">
                                <label class="inline-flex items-center cursor-pointer text-xs font-bold bg-blue-700 hover:bg-blue-500 text-white py-2 px-3 rounded-lg">
                                    <span class="mx-2" wire:loading wire:target="image">
                                        <i class="fas fa-spinner fa-pulse"></i>
                                    </span>
                                    Cargar imagen
                                    <input type="file" class="hidden" wire:model="image">
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
            @error('image')
                <div class="text-danger mt-2 ml-3">
                    <span class="">{{ $message }}
                    </span>
                </div>
            @enderror
        </div>
    </div>

    <div class="flex justify-center mt-5 gap-x-5">
        <a href="{{ route('inventario.index') }}" type="button" class=" text-sm bg-red-500 hover:bg-red-400 text-white font-bold py-2 px-4 rounded-lg" wire:navigate>
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
                    window.location.href = "{{ route('inventario.index') }}";
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

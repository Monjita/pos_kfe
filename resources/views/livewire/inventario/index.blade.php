<?php

use Livewire\Volt\Component;
use Illuminate\Support\Facades\Auth;
use App\Models\Producto;
use Livewire\WithPagination;

new class extends Component {
    use WithPagination;
    protected $paginationTheme = 'tailwind';

    protected $listeners = [ 'eliminarProducto', 'desactivarProducto'];
    
    public function render(): mixed
    {
        return view('livewire.inventario.index', [
            'productos' => Producto::with('stock', 'unidades')
                ->orderBy('producto.id', 'asc')
                ->paginate(10),
        ]);
    }

    public function eliminarProducto($claveProducto){
        $producto = Producto::where('clave','=',$claveProducto)->first();
        $producto->delete();
    }

    public function desactivarProducto($claveProducto){
            $producto = Producto::where('clave','=',$claveProducto)->first();
            $producto->status = ($producto->status == 'A') ? 'B' : 'A';
            $producto->save();
    }
}; ?>

<div class="">
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200 border border-gray-200">
            <thead class="bg-gray-100">
                <tr class="text-base font-medium text-body">
                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Producto</th>
                    {{-- <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Unidad</th> --}}
                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Precio</th>
                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Stock</th>
                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Acciones</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($productos as $producto)
                    <tr class="bg-white even:bg-gray-50">
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
                        {{-- <td class="text-center px-6 whitespace-nowrap text-sm text-gray-900">
                            {{ $producto->unidades->clave ?? 'NA'}}
                        </td> --}}
                        <td class="text-center px-6 whitespace-nowrap text-sm text-gray-900">
                            $ {{ number_format($producto->precios->first()->precio,2,'.',',') }}
                            {{-- $ 123.00 --}}
                        </td>
                        <td class="text-center px-6 whitespace-nowrap text-sm text-gray-900">
                            {{ $producto->stock->sum('exist') }}
                            {{-- 10 --}}
                        </td>
                        <td class="text-center px-6 whitespace-nowrap text-sm text-gray-900">
                            <i class="fa-solid fa-circle {{ $producto->status == 'A' ? 'text-green-500' : 'text-red-500' }} fa-xs"></i>
                            <span class="">{{ $producto->status == 'A' ? 'Activo' : 'Inactivo' }}</span>
                        </td>
                        <td class="w-56">
                            <div class="flex justify-center items-center">
                                {{-- @if(auth()->user()->can('inventario->producto->editar')) --}}
                                    <div class="relative flex items-center group justify-center">
                                        <a class="text-sm text-opacity-100 border border-transparent text-blue-500 hover:bg-blue-100 font-bold py-2 px-2 rounded-lg" href="{{ route('producto.edit', $producto->id) }}" wire:navigate>
                                            <i class="fa-solid fa-pen-to-square fa-lg"></i>
                                        </a>
                                        <div class="absolute top-0 -mt-10 flex flex-wrap items-center justify-center  hidden group-hover:flex">
                                            <span class="relative z-10 px-3 py-2 text-sm font-normal leading-none whitespace-nowrap bg-neutral-800 text-white shadow-lg rounded-lg">
                                                Editar
                                            </span>
                                            <div class="w-5 h-5 -mt-4 mb-4 rotate-45 bg-neutral-800"></div>
                                        </div>
                                    </div>
                                {{-- @endif --}}
                                {{-- @if(auth()->user()->can('inventario->producto->editar')) --}}
                                    <div class="relative flex items-center group justify-center mx-1">
                                        {{-- <a class="btn btn-sm btn-outline-sucursal {{ $producto->status == 'A' ? 'text-success' : 'text-danger' }} {{ $producto->status == 'A' ? 'hover:bg-lime-100' : 'hover:bg-red-100' }}  mx-1" onclick="modalDesactProd('{{ $producto->clave }}', '{{ $producto->nombre }}', '{{ $producto->status }}')"> --}}
                                        <button class="text-sm text-opacity-100 border border-transparent {{ $producto->status == 'A' ? 'text-green-500 hover:bg-lime-100' : 'text-red-500 hover:bg-red-100' }} font-bold py-2 px-2 rounded-lg"  onclick="modalDesactProd('{{ $producto->clave }}', '{{ $producto->nombre }}', '{{ $producto->status }}')">
                                            <div class="z-0">
                                                <i class="fa-solid {{ $producto->status == 'A' ? 'fa-toggle-on' : 'fa-toggle-off' }} fa-lg "></i>
                                            </div>
                                        </button>
                                        <div class="absolute top-0 -mt-10 flex flex-wrap items-center justify-center  hidden group-hover:flex">
                                            <span class="relative z-10 px-3 py-2 text-sm font-normal leading-none whitespace-nowrap bg-neutral-800 text-white shadow-lg rounded-lg">
                                            {{ $producto->status == 'A' ? 'Desactivar producto' : 'Activar producto' }}
                                            </span>
                                            <div class="w-5 h-5 -mt-4 mb-4 rotate-45 bg-neutral-800"></div>
                                        </div>
                                    </div>
                                {{-- @endif --}}
                                {{-- @if(auth()->user()->can('inventario->producto->eliminar')) --}}
                                    <div class="relative flex items-center group justify-center">
                                        <button class="text-sm text-opacity-100 border border-transparent text-red-500 hover:bg-red-100 font-bold py-2 px-2 rounded-lg" onclick="modalEliminarProducto('{{ $producto->clave }}', '{{ $producto->nombre }}')">
                                            <div class="z-0">
                                                <i class="fa-regular fa-trash-can fa-lg"></i>
                                            </div>
                                        </button>

                                        <div class="absolute top-0 -mt-10 mr-32 flex items-center hidden group-hover:flex">
                                            <span class="relative w-44 z-10 px-3 py-2 text-sm font-normal leading-none whitespace-nowrap bg-neutral-800 text-white shadow-lg rounded-lg truncate   text-center ">
                                            Eliminar {{ Str::limit($producto->nombre, $limit = 10, $end = '...') }}
                                            </span>
                                            <div class="w-5 h-5 -ml-8 -mb-4 rotate-45 bg-neutral-800"></div>
                                        </div>
                                    </div>
                                {{-- @endif --}}
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr class="bg-white even:bg-gray-50">
                        <td colspan="6" class="text-center px-6 py-4 whitespace-nowrap text-base text-red-500">
                            <i class="fa-solid fa-circle-exclamation fa-lg"></i>
                            ¡Aún no has creado tu primer producto!
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
     <div>
        <div class="flex justify-center mt-4 w-full">
            {{ $productos->links() }}
        </div>
    </div>
</div>

@push('js')
    <script>
        function modalDesactProd(clave,nombre,status){
            Swal.fire({
                title: '¿Estás seguro?',
                text: status=='A' ? 'Desactivar ' + nombre : 'Activar ' + nombre,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc2626',
                cancelButtonColor: '#6b7280',
                confirmButtonText: status=='A' ? 'Sí, desactivar' : 'Sí, activar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    // window.livewire.emit('desactivarProducto',  claveProducto);
                      Livewire.dispatch('desactivarProducto', {
                            claveProducto: clave
                        })
                }
            })
        }

        function modalEliminarProducto(clave,nombre){
            Swal.fire({
                title: '¿Estás seguro?',
                text: 'Eliminar ' + nombre,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc2626',
                cancelButtonColor: '#6b7280',
                confirmButtonText: 'Sí, eliminar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                      Livewire.dispatch('eliminarProducto', {
                            claveProducto: clave
                        })
                }
            })
        }

    </script>
@endpush
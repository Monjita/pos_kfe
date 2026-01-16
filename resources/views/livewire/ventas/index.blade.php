<?php

use Livewire\Volt\Component;
use Illuminate\Support\Facades\Auth;
use App\Models\NotaVenta;
use Livewire\WithPagination;

new class extends Component {
    use WithPagination;
    protected $paginationTheme = 'tailwind';

    public function render(): mixed
    {
        $query = NotaVenta::with(['cliente', 'vendedor'])
            ->orderBy('id', 'desc');
        return view('livewire.ventas.index', [
            'notas' => $query->paginate(8),
        ]);
    }
}; ?>

<div class="">
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200 border border-gray-200">
            <thead class="bg-gray-100">
                <tr class="text-base font-medium text-body">
                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Folio</th>
                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Fecha de emision</th>
                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">RFC</th>
                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Razon social</th>
                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Estatus</th>
                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Total ($)</th>
                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Acciones</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($notas as $nota)
                    <tr class="bg-white even:bg-gray-50">
                        <td class="text-center px-6 whitespace-nowrap text-sm text-gray-900">
                            {{ $nota->serie . str_pad($nota->folio, 5, 0, STR_PAD_LEFT) }}
                        </td>
                        <td class="text-center px-6 whitespace-nowrap text-sm text-gray-900">{{ \Carbon\Carbon::parse($nota->fecha_doc)->formatLocalized('%d/%b/%y') }}</td>
                        <td class="text-center px-6 whitespace-nowrap text-sm text-gray-900">{{ $nota->cliente->rfc }}</td>
                        <td class="text-center px-6 whitespace-nowrap text-sm text-gray-900">{{ Str::limit( $nota->cliente->razon_social, $limit = 40, $end = '...') }}</td>
                        <td class="text-center px-6 whitespace-nowrap text-sm text-gray-900 {{ $nota->status == 'C' ? 'text-red-500' : '' }}">
                            {{ $mapeoEstatus[$nota->status] ?? 'Desconocido' }}
                        </td>
                        <td class="text-center px-6 whitespace-nowrap text-sm text-gray-900">{{ $nota->importe }}</td>
                        <td class="w-56">
                            <div class="flex justify-center items-center">
                                <div class="relative flex items-center group justify-center">
                                    <a class="text-sm text-opacity-100 border border-transparent text-blue-500 hover:bg-blue-100 font-bold py-2 px-2 rounded-lg" href="{{ route('inventario.index', $nota->id) }}" wire:navigate>
                                        <i class="fa-solid fa-pen-to-square fa-lg"></i>
                                    </a>
                                    <div class="absolute top-0 -mt-10 flex flex-wrap items-center justify-center  hidden group-hover:flex">
                                        <span class="relative z-10 px-3 py-2 text-sm font-normal leading-none whitespace-nowrap bg-neutral-800 text-white shadow-lg rounded-lg">
                                            Imprimir carta
                                        </span>
                                        <div class="w-5 h-5 -mt-4 mb-4 rotate-45 bg-neutral-800"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="relative flex items-center group justify-center">
                                <button class="text-sm text-opacity-100 border border-transparent text-red-500 hover:bg-red-100 font-bold py-2 px-2 rounded-lg" onclick="modalEliminar('{{ $nota->id }}', '{{ $nota->folio }}')">
                                    <div class="z-0">
                                        <i class="fa-regular fa-trash-can fa-lg"></i>
                                    </div>
                                </button>

                                <div class="absolute top-0 -mt-10 mr-32 flex items-center hidden group-hover:flex">
                                    <span class="relative w-44 z-10 px-3 py-2 text-sm font-normal leading-none whitespace-nowrap bg-neutral-800 text-white shadow-lg rounded-lg truncate   text-center ">
                                    {{-- Eliminar {{ Str::limit($nota->nombre, $limit = 10, $end = '...') }} --}}
                                        Cancelar nota
                                    </span>
                                    <div class="w-5 h-5 -ml-8 -mb-4 rotate-45 bg-neutral-800"></div>
                                </div>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr class="bg-white even:bg-gray-50">
                        <td colspan="8" class="text-center px-6 py-4 whitespace-nowrap text-base text-red-500">
                            <i class="fa-solid fa-circle-exclamation fa-lg"></i>
                            ¡Aún no has creado tu primera nota de venta!
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
     <div>
        <div class="flex justify-center mt-4 w-full">
            {{ $notas->links() }}
        </div>
    </div>
</div>

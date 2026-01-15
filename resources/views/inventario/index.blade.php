<x-app-layout>
    <x-slot name="title">
        Inventario
    </x-slot>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Inventario') }}
        </h2>
    </x-slot>

    <div class="">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 py-5">
            <a href="{{ route('producto.create') }}" type="button" class="ml-2 sm:ml-0 text-sm bg-blue-700 hover:bg-blue-500 text-white font-bold py-2 px-2 rounded-lg" wire:navigate>
                {{-- <i class="fa-solid fa-pen-to-square mr-1"></i> --}}
                <i class="fa-solid fa-circle-plus mr-1"></i>
                Crear producto
            </a>
        </div>

        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                {{-- <div class="p-6 text-gray-900">
                    {{ __("index inventario") }}
                </div> --}}
                <div class="p-6">
                    <livewire:inventario.index />
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
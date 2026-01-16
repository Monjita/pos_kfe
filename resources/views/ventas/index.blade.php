<x-app-layout>
    <x-slot name="title">
        Ventas
    </x-slot>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Ventas') }}
        </h2>
    </x-slot>

    <div class="">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 py-5">
            <a href="{{ route('notaVenta.create') }}" type="button" class="ml-2 sm:ml-0 text-sm bg-blue-700 hover:bg-blue-500 text-white font-bold py-2 px-2 rounded-lg" wire:navigate>
                <i class="fa-solid fa-circle-plus mr-1"></i>
                Agregar nota de venta
            </a>
        </div>

        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <livewire:ventas.index />
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
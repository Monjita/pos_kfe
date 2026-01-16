<x-app-layout>
    <x-slot name="title">
        Nota de venta
    </x-slot>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Nueva nota de venta') }}
        </h2>
    </x-slot>

    <div class="my-5">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="m-4">
                    <livewire:notaventa.create />
                </div>
            </div>
        </div>
    </div>
    
</x-app-layout>
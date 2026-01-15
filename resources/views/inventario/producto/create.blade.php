<x-app-layout>
    <x-slot name="title">
        Crear producto
    </x-slot>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Crear producto') }}
        </h2>
    </x-slot>

    <div class="">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 py-5">
            <a href="{{ route('inventario.index') }}" type="button" class="ml-2 sm:ml-0 text-sm bg-red-500 hover:bg-red-400 text-white font-bold py-2 px-2 rounded-lg">
                <i class="fa-regular fa-circle-xmark mr-1"></i>
                Cancelar
            </a>
        </div>

        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                {{-- <div class="p-6 text-gray-900">
                    {{ __("index inventario") }}
                </div> --}}
                <div class="p-6">
                    <livewire:producto.create />
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
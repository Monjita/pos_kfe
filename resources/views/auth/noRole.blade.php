

<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('No Role') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg py-5">
                <div class="text-center">
                    <h1 class="text-2xl font-bold text-red-600">
                        Acceso restringido
                    </h1>

                    <p class="mt-4">
                        Tu usuario no tiene un rol asignado.
                    </p>

                    <p class="mt-2 text-sm text-gray-500">
                        Contacta al administrador del sistema.
                    </p>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
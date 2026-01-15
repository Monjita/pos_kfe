<?php

use Livewire\Volt\Component;
use Illuminate\Support\Facades\Auth;
use App\Models\Producto;

new class extends Component {
    public string $clave = '';
    public string $nombre = '';
    public string $marca = '';
    public string $linea = '';
    public string $categoria = '';

    public function save(){
        $this->validate([
                'clave' => 'required|regex:/^[a-zA-Z0-9\-\/]+$/|unique:producto,clave,NULL,id,deleted_at,NULL',
                'nombre' => 'required|string|max:255',
                // 'costo' => 'numeric',
                // 'marca' => 'required|string|max:255',
                // 'linea' => 'required|string|max:255',
                // 'categoria' => 'required|string|max:255',
             ],[
                'clave.required' => 'La clave es obligatoria.',
                'clave.unique' => 'La clave ya existe.',
                'clave.regex' => 'La clave solo puede contener letras, números, guiones y diagonales.',
                'nombre.required' => 'El nombre es obligatorio.',
            ]);

        $producto = Producto::create([
            'clave' => $this->clave,
            'nombre' => $this->nombre,
            'marca' => $this->marca,
            'linea' => $this->linea,
            'categoria' => $this->categoria,
            'usuario' => Auth::id(),
        ]);

        $producto->save();

        $this->redirect(route('inventario.index', absolute: false), navigate: true);
    }
}; ?>

<div>
    <div class="sm:flex w-full sm:gap-x-10">
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

        {{-- <div class="w-full mt-2 sm:mt-0">
            <x-input-label for="precio" :value="__('Precio')" />
            <x-text-input wire:model="precio" id="precio" name="precio" type="text" class="mt-1 block w-full" required autocomplete="precio" />
            <x-input-error class="mt-2" :messages="$errors->get('precio')" />
        </div> --}}
    </div>

    <div class="sm:flex w-full sm:gap-x-10 mt-2">
        <div class="w-full">
            <x-input-label for="marca" :value="__('Marca')" />
            <x-text-input wire:model="marca" id="marca" name="marca" type="text" class="mt-1 block w-full" required autofocus autocomplete="marca" />
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

    <div class="flex justify-end mt-5">
        <button type="button" wire:click="save" class="ml-2 sm:ml-0 text-sm bg-blue-700 hover:bg-blue-500 text-white font-bold py-2 px-4 rounded-lg">
            <i class="fa-regular fa-floppy-disk mr-1"></i>
            Guardar
        </button>
    </div>
</div>

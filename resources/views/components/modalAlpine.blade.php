@props(['name'])

<div
    x-data="{ open: false }"
    x-on:open-modal.window="open = ($event.detail.name === '{{ $name }}')"
    x-on:close-modal.window="open = false"
    x-show="open"
    x-transition
    x-cloak
    class="fixed inset-0 z-50 flex items-center justify-center"
>
    <!-- Overlay -->
    <div
        class="absolute inset-0 bg-black bg-opacity-50"
        @click="open = false"
    ></div>

    <!-- Modal -->
    <div class="relative bg-white rounded-lg shadow-xl w-full max-w-lg mx-4">
        <div class="p-4">
            {{ $slot }}
        </div>

        <div class="flex justify-end gap-2 p-4 border-t">
            <button
                @click="open = false"
                class="px-3 py-1 bg-gray-200 rounded hover:bg-gray-300"
            >
                Cancelar
            </button>
        </div>
    </div>
</div>

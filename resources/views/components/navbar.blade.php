<header x-data="{ open: false }" class="border-b border-gray-200 bg-white px-4 py-6">
    <nav class="mx-auto flex max-w-7xl items-center justify-between">
        <x-logo />

        <x-links class="hidden space-x-6 md:flex" />

        <div class="flex items-center text-gray-500">
            <button @click="$dispatch('open-search')" class="mr-4 hover:text-gray-600 md:mr-0">
                <x-heroicon-o-search class="h-6 w-6 text-gray-500 transition hover:text-gray-600" />
            </button>

            <button @click="open = true" class="hover:text-gray-600 md:hidden">
                <x-heroicon-o-menu class="h-6 w-6 text-gray-500 transition hover:text-gray-600" />
            </button>
        </div>

        <div x-cloak x-show="open" x-trap.inert.noreturn.noscroll="open" class="fixed inset-0 z-50">
            <div
                x-show="open"
                x-transition:enter="duration-300 ease-out"
                x-transition:enter-start="opacity-0"
                x-transition:enter-end="opacity-100"
                x-transition:leave="duration-200 ease-in"
                x-transition:leave-start="opacity-100"
                x-transition:leave-end="opacity-0"
                class="fixed inset-0 bg-gray-900/50 backdrop-blur-sm"
            />

            <div
                x-show="open"
                @click.outside="open = false"
                x-transition:enter="ease-out duration-300"
                x-transition:enter-start="-translate-x-full"
                x-transition:leave="ease-in duration-200"
                x-transition:leave-end="-translate-x-full"
                class="relative min-h-screen w-full max-w-xs bg-white px-4 py-6 shadow-2xl"
            >
                <div class="flex items-center justify-between">
                    <x-logo />

                    <button class="text-gray-500 hover:text-gray-600">
                        <x-heroicon-o-x @click="open = false" class="h-6 w-6 text-gray-500 transition hover:text-gray-600" />
                    </button>
                </div>

                <x-links class="mt-12 flex flex-col space-y-4" />
            </div>
        </div>
    </nav>
</header>

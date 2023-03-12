<section
    x-cloak
    x-data="{ open: false}"
    x-show="open"
    x-trap.inert.noreturn.noscroll="open"
    class="fixed inset-0 z-50 overflow-y-auto"
    @open-search.window="open = $event.detail"
    x-on:keydown.escape="open = false"
>
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

    <div class="px-4 pt-12">
        <div
            x-show="open"
            x-transition:enter="duration-300 ease-out"
            x-transition:enter-start="opacity-0 scale-95"
            x-transition:enter-end="opacity-100 scale-100"
            x-transition:leave="duration-200 ease-in"
            x-transition:leave-start="opacity-100 scale-100"
            x-transition:leave-end="opacity-0 scale-95"
            class="relative mx-auto max-w-xl overflow-hidden rounded-xl bg-white shadow-2xl ring-1 ring-black/5"
        >
            <div class="flex items-center border-b px-4" @click.outside="open = false">
                <x-heroicon-o-search class="h-6 w-6 text-gray-500" />
                <label for="search">
                    <input
                        type="text"
                        id="search"
                        class="w-full border-0 bg-transparent py-4 placeholder:text-gray-500 focus:ring-0"
                        placeholder="Encontre qualquer coisa..."
                        wire:model.debounce.300ms="filter"
                    />
                </label>
            </div>

            <div class="max-h-96 divide-y divide-gray-200 overflow-y-auto focus-visible:outline-none">
                @forelse ($results as $result)
                    <div class="group">
                        <a href="{{ route($result->route, $result->slug) }}" class="group flex items-center justify-between p-4 focus-visible:outline-none">
                            <span class="truncate font-medium group-hover:text-blue-600 transition group-focus-visible:text-blue-600">
                                {{ $result->title }}
                            </span>
                            <span class="ml-4 text-sm text-gray-500">
                                {{ $result->type }}
                            </span>
                        </a>
                    </div>
                @empty
                    @if (!empty($filter))
                        <div class="bg-gray-50 py-20 px-16 text-center">
                            <h2 class="font-semibold text-gray-900">Nenhum resultado encontrado</h2>
                            <p class="mt-2 text-sm leading-6 text-gray-600">Não encontramos nada com esse termo no momento, tente pesquisar outra coisa.</p>
                        </div>
                    @endif
                @endforelse
            </div>
        </div>
    </div>
</section>

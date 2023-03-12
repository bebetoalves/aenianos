@if ($paginator->hasPages())
    <div class="mt-4 flex items-center justify-between rounded-lg border border-gray-200 bg-white p-4 text-gray-500">
        @if ($paginator->onFirstPage())
            <span class="cursor-not-allowed rounded-md border bg-gray-100 px-4  py-2 text-sm font-medium transition md:hidden">
                Anterior
            </span>
        @else
            <button wire:click="previousPage" class="rounded-md border border-gray-200 bg-white px-4 py-2 text-sm font-medium text-gray-500 transition hover:bg-gray-500/5 md:hidden">
                Anterior
            </button>
        @endif

        <p class="hidden text-sm text-gray-700 md:block">
            <span>Exibindo de</span> <strong>{{ $paginator->firstItem() }}</strong> <span>até</span> <strong>{{ $paginator->lastItem() }}</strong> <span>de</span> <strong>{{ $paginator->total() }}</strong> <span>resultados</span>
        </p>

        <p class="text-sm text-gray-700 md:hidden">
            <span>Página</span> <strong>{{ $paginator->currentPage() }}</strong> <span>de</span> <strong>{{ $paginator->lastPage() }}</strong>
        </p>

        @if ($paginator->hasMorePages())
            <button wire:click="nextPage" class="rounded-md border border-gray-200 bg-white px-4 py-2 text-sm font-medium text-gray-500 transition hover:bg-gray-500/5 md:hidden">
                Próximo
            </button>
        @else
            <span class="cursor-not-allowed rounded-md border bg-gray-100 px-4  py-2 text-sm font-medium transition md:hidden">
                Próximo
            </span>
        @endif

        <div class="hidden rounded-lg border py-3 md:block">
            <ol class="flex items-center divide-x divide-gray-300 text-sm text-gray-500">
                @if (false === $paginator->onFirstPage())
                    <li>
                        <button wire:click="previousPage" class="relative -my-3 flex h-8 min-w-[2rem] items-center justify-center rounded-md px-1.5 font-medium outline-none hover:bg-gray-500/5">
                            <x-heroicon-o-chevron-left class="h-5 w-5 text-blue-700" />
                        </button>
                    </li>
                @endif

                @foreach ($elements as $element)
                    @if (is_string($element))
                        <li>
                            <span class="relative -my-3 flex h-8 min-w-[2rem] items-center justify-center rounded-md px-1.5 font-medium outline-none">
                                <span>{{ $element }}</span>
                            </span>
                        </li>
                    @endif

                    @if (is_array($element))
                        @foreach ($element as $page => $url)
                            @if ($page == $paginator->currentPage())
                                <li>
                                    <span class="relative -my-3 flex h-8 min-w-[2rem] items-center justify-center rounded-md bg-blue-500/10 px-1.5 font-medium text-blue-700 outline-none ring-2 ring-blue-500">
                                        <span>{{ $page }}</span>
                                    </span>
                                </li>
                            @else
                                <li>
                                    <button
                                        type="button"
                                        wire:click="gotoPage({{ $page }}, '{{ $paginator->getPageName() }}')"
                                        class="relative -my-3 flex h-8 min-w-[2rem] items-center justify-center rounded-md px-1.5 font-medium outline-none hover:bg-gray-500/5"
                                    >
                                        {{ $page }}
                                    </button>
                                </li>
                            @endif
                        @endforeach
                    @endif
                @endforeach

                @if ($paginator->hasMorePages())
                    <li>
                        <button wire:click="nextPage" class="relative -my-3 flex h-8 min-w-[2rem] items-center justify-center rounded-md px-1.5 font-medium outline-none hover:bg-gray-500/5">
                            <x-heroicon-o-chevron-right class="h-5 w-5 text-blue-700" />
                        </button>
                    </li>
                @endif
            </ol>
        </div>
    </div>
@endif

<section>
    <div class="rounded-lg border border-gray-200 bg-white p-4 sm:flex-row">
        <div class="w-full shrink-0 sm:flex sm:w-auto">
            <div class="relative mb-4 w-full shrink-0 sm:mb-0 sm:mr-4 sm:w-64 lg:w-96">
                <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                    <x-heroicon-o-search class="h-5 w-5 text-gray-500" />
                </div>

                <label for="search">
                    <input
                        type="text"
                        id="search"
                        class="block w-full rounded-lg border-gray-200 p-2.5 py-2 pl-10 text-sm shadow-sm outline-none transition duration-75 focus:border-blue-500 focus:ring-1 focus:ring-inset focus:ring-blue-500 disabled:opacity-70"
                        placeholder="Pesquisar..."
                        wire:model.debounce.300ms="title"
                    />
                </label>
            </div>

            <label for="categories">
                <select id="categories" wire:model.debounce.300ms="category" class="block w-full rounded-lg border border-gray-200 bg-white p-2.5 py-2 text-sm text-gray-500 outline-none transition duration-75 focus:border-blue-500 focus:ring-1 focus:ring-inset focus:ring-blue-500 sm:w-44">
                    <option value="">Todas as categorias</option>

                    @foreach ($categories as $value => $description)
                        <option value="{{ $value }}">
                            {{ $description }}
                        </option>
                    @endforeach
                </select>
            </label>
        </div>
    </div>

    <div class="mt-4 grid grid-cols-1 gap-2 sm:grid-cols-2 md:grid-cols-3 md:gap-4 lg:grid-cols-4">
        @forelse ($projects as $project)
            <div class="group overflow-hidden rounded-lg shadow">
                <x-project-miniature :project="$project" />
            </div>
        @empty
            <div class="col-span-full row-span-full rounded-lg border border-gray-200 bg-white p-16 text-center text-gray-500">
                <h3 class="mt-4 text-2xl text-gray-500">Não há projetos no momento!</h3>
            </div>
        @endforelse
    </div>

    {{ $projects->links('components.pagination') }}
</section>

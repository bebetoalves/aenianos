<x-layout>
    <x-slot:title>{{ $project->title }}</x-slot:title>
    <x-slot:seo>{!! seo()->for($project) !!}</x-slot:seo>

    <x-slot:header>
        <div class="relative mt-12 overflow-hidden rounded-lg bg-cover bg-center bg-no-repeat p-4 shadow"
             style="background-image: url('{{ image_url($project->cover) }}')">
            <div class="absolute inset-0 z-10 bg-gray-900/50 backdrop-blur-sm">
            </div>

            <div class="relative z-20 flex flex-col items-center text-white lg:flex-row">
                <figure class="w-52 overflow-hidden rounded-lg border-4 border-white bg-white lg:mr-4">
                    <img src="{{ image_url($project->miniature) }}" alt="{{ $project->title }}"
                         class="w-full object-cover object-center"/>
                </figure>

                <section class="mt-4 flex flex-1 flex-col space-y-6 lg:mt-0">
                    <div class="flex flex-col text-center lg:text-left">
                        <h1 class="text-2xl font-bold leading-relaxed">
                            {{ $project->title }}
                        </h1>

                        <div class="flex flex-wrap items-center justify-center gap-2 text-gray-200 lg:justify-start">
                            <x-badge :color="$project->season->color()" :label="$project->season->description">
                                <x-slot:icon>
                                    @if ($project->season->is(\App\Enums\Season::WINTER))
                                        <x-icons.snowflake class="mr-1 h-auto w-3"/>
                                    @elseif (@$project->season->is(\App\Enums\Season::SPRING))
                                        <x-icons.flower class="mr-1 h-auto w-3"/>
                                    @elseif (@$project->season->is(\App\Enums\Season::SUMMER))
                                        <x-icons.sun class="mr-1 h-auto w-3"/>
                                    @elseif (@$project->season->is(\App\Enums\Season::FALL))
                                        <x-icons.leaf class="mr-1 h-auto w-3"/>
                                    @endif
                                </x-slot:icon>
                            </x-badge>

                            <x-badge color="slate" class="py-1" :label="$project->year">
                                <x-slot:icon>
                                    <x-heroicon-o-calendar class="mr-1 h-auto w-3"/>
                                </x-slot:icon>
                            </x-badge>

                            <x-badge color="purple" :label="$project->category->description">
                                <x-slot:icon>
                                    @if ($project->category->is(\App\Enums\Category::SERIES))
                                        <x-icons.tv class="mr-1 h-auto w-3"/>
                                    @elseif (@$project->category->is(\App\Enums\Category::MOVIE))
                                        <x-icons.ticket class="mr-1 h-auto w-3"/>
                                    @elseif (@$project->category->in([\App\Enums\Category::OVA, \App\Enums\Category::ONA]))
                                        <x-icons.disc class="mr-1 h-auto w-3"/>
                                    @elseif (@$project->category->is(\App\Enums\Category::SPECIAL))
                                        <x-icons.star class="mr-1 h-auto w-3"/>
                                    @endif
                                </x-slot:icon>
                            </x-badge>

                            @foreach ($project->genres as $genre)
                                <x-badge class="py-1" color="orange" :label="$genre->name">
                                    <x-slot:icon>
                                        <x-heroicon-o-tag class="mr-1 h-auto w-3"/>
                                    </x-slot:icon>
                                </x-badge>
                            @endforeach
                        </div>
                    </div>

                    <div class="text-center lg:text-left">
                        <h2 class="hidden text-xl font-semibold leading-relaxed tracking-wide lg:block">Sinopse</h2>

                        <span class="text-center lg:text-left">{{ $project->synopsis }}</span>
                    </div>

                    <div class="flex flex-nowrap justify-center space-x-12 lg:justify-start">
                        <div class="text-center lg:text-left">
                            <h2 class="text-xl font-semibold leading-relaxed tracking-wide">Nome Alternativo</h2>
                            <span class="text-gray-200">{{ $project->alternative_title ?? 'Não informado' }}</span>
                        </div>

                        <div class="text-center lg:text-left">
                            <h2 class="text-xl font-bold leading-relaxed">Episódios</h2>
                            <span class="text-gray-200">{{ $project->episodes }}</span>
                        </div>
                    </div>
                </section>
            </div>
        </div>
    </x-slot:header>

    <x-card>
        <x-slot:title>Downloads</x-slot:title>
        <x-slot:icon>
            <x-heroicon-o-download class="h-5 w-5"/>
        </x-slot:icon>

        <div x-data="{ activeTab:  '{{ $qualities->first()?->value }}' }">
            <div class="grid gap-4 sm:grid-cols-4">
                @forelse ($qualities as $quality)
                    <button
                        @click="activeTab = '{{ $quality->value }}'"
                        class="flex items-center justify-center rounded-lg border px-3 py-2 text-lg transition"
                        x-bind:class="activeTab === '{{ $quality }}' ? 'bg-blue-600 text-white border-blue-600' : 'bg-white text-gray-600 hover:bg-blue-600 hover:text-white hover:border-blue-600'"
                    >
                        <x-heroicon-o-download class="mr-1 h-6 w-5"/>
                        <span class="text-sm font-medium">
                            {{ $quality->description }}
                        </span>
                    </button>
                @empty
                    <span class="col-span-full text-gray-500">Não há downloads no momento!</span>
                @endforelse
            </div>

            @foreach ($links as $quality => $item)
                <div x-cloak class="mt-4 grid gap-4 lg:grid-cols-2" x-show="activeTab === '{{ $quality }}'" x-transition.in.opacity.duration.500ms>
                    @foreach ($item as $name => $link)
                        <div
                            class="inline-flex items-center justify-between rounded-lg border border-gray-200 bg-gray-50 p-4 text-gray-500">
                            <span class="mr-4 flex items-center rounded-lg text-sm text-gray-600">
                                <x-heroicon-o-video-camera class="mr-1 w-5"/>
                                <span>{{ $name }}</span>
                            </span>
                            <ul class="flex gap-5">
                                @foreach ($link as $url)
                                    <li>
                                        <a href="{{ route('links.show', $url->id) }}" class="block h-8 w-8 overflow-hidden rounded-full"
                                           target="_blank">
                                            <img
                                                class="h-full w-full object-cover object-center transition hover:opacity-80"
                                                src="{{ $url->server->icon }}"
                                                alt="{{ $url->server->name }}"
                                            />
                                        </a>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    @endforeach
                </div>
            @endforeach
        </div>
    </x-card>

    <x-card class="mt-4">
        <x-slot:title>Projetos Relacionados</x-slot:title>
        <x-slot:icon>
            <x-heroicon-o-video-camera class="h-5 w-5"/>
        </x-slot:icon>

        <section class="grid grid-cols-1 gap-4 md:grid-cols-5">
            @forelse ($project->relatedProjects as $project)
                <div class="overflow-hidden rounded-lg shadow">
                    <x-project-miniature :project="$project"/>
                </div>
            @empty
                <span class="col-span-full text-gray-500">Não há projetos relacionados no momento!</span>
            @endforelse
        </section>
    </x-card>

    <x-comments class="mt-4"/>
</x-layout>

<x-card>
    <x-slot:title>Progresso dos Projetos</x-slot:title>
    <x-slot:icon>
        <x-heroicon-o-flag class="h-5 w-5"/>
    </x-slot:icon>

    <section class="flex flex-col space-y-2 md:space-y-4">
        @forelse ($progression as $progress)
            <div class="relative overflow-hidden rounded-lg bg-white p-4 shadow">
                <div class="absolute inset-0 bg-cover bg-no-repeat" style="background-image: url({{ image_url($progress->project->miniature) }})"></div>

                <div class="absolute inset-0 bg-gray-900/50 backdrop-blur-sm"></div>

                <div class="relative flex flex-col justify-between">
                    <div class="text-white">
                        <h3 class="truncate text-lg font-bold">{{ $progress->project->title }}</h3>
                        <span class="text-sm tracking-wide text-gray-200">{{ $progress->media }}</span>
                    </div>

                    <div class="mt-4 flex space-x-1">
                        @foreach ($progress->states as $state)
                            <x-badge :label="$state->key" :tooltip="$state->description" :color="$state->color()" />
                        @endforeach
                    </div>
                </div>
            </div>
        @empty
            <span class="text-gray-500">Não há progresso disponível no momento!</span>
        @endforelse
    </section>
</x-card>

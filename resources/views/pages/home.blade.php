<x-layout>
    <x-slot:title>Página Inicial</x-slot:title>

    <x-slot:header>
        <div class="mt-12 grid grid-cols-6 grid-rows-1 gap-2 md:grid-cols-4 md:grid-rows-2 md:gap-4">
            @foreach ($highlights as $highlight)
                <a href="{{ route('projects.show', $highlight->project->slug) }}"
                   class="group relative col-span-3 overflow-hidden rounded-lg shadow first:col-span-full md:col-span-1 md:first:col-span-2 md:first:row-span-2">
                    <img
                        class="h-full w-full object-cover object-center transition duration-300 group-hover:scale-110"
                        src="{{ image_url($highlight->cover()) }}"
                        alt="{{ $highlight->project->title }}"
                    />

                    <span class="absolute inset-x-0 bottom-0 truncate whitespace-nowrap bg-gray-900/50 px-4 py-2.5 text-lg font-extrabold text-white backdrop-blur-sm">
                        {{ $highlight->project->title }}
                    </span>
                </a>
            @endforeach
        </div>
    </x-slot:header>

    <div class="grid grid-cols-1 gap-2 md:grid-cols-2 md:gap-4">
        @foreach ($posts as $post)
            <x-post-card
                :slug="$post->slug"
                :title="$post->title"
                :content="$post->content"
                :cover="$post->image"
                :author="$post->user->name"
                :date="$post->created_at"
            />
        @endforeach
    </div>

    <div class="mt-12 text-center">
        <a href="{{ route('posts') }}"
           class="inline-block rounded bg-blue-500 px-4 py-2 font-medium text-white transition hover:bg-blue-600">
            Mais Postagens
        </a>
    </div>
</x-layout>

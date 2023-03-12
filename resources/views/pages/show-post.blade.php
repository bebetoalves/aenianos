<x-layout>
    <x-slot:title>{{ $post->title }}</x-slot:title>

    <div class="flex flex-col rounded-lg border border-gray-200 bg-white p-4">
        <div>
            <h3 class="text-xl font-bold">
                {{ $post->title }}
            </h3>

            <ul class="mt-1 flex items-center space-x-4 text-sm text-gray-500">
                <li class="flex items-center">
                    <x-heroicon-o-user-circle class="mr-px h-5 w-5"/>
                    <span>{{ $post->user->name }}</span>
                </li>

                <li class="flex items-center">
                    <x-heroicon-o-clock class="mr-px h-5 w-5"/>
                    <span>{{ $post->created_at->format('d/m/Y') }}</span>
                </li>

                <li class="flex items-center">
                    <x-heroicon-o-chat-alt-2 class="mr-px h-5 w-5"/>
                    <a href="{{ sprintf('%s#%s', route('posts.show', $post->slug), 'disqus_thread') }}">
                        0 comentário
                    </a>
                </li>
            </ul>
        </div>

        <figure class="mt-4 h-64 overflow-hidden rounded-lg lg:h-96">
            <img src="{{ image_url($post->image) }}" alt="{{ $post->title }}" class="h-full w-full object-cover object-center shadow" />
        </figure>

        <x-markdown class="prose mt-4 max-w-none">
            {{ $post->content }}
        </x-markdown>
    </div>

    <x-comments class="mt-4"/>
</x-layout>

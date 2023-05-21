<div class="flex flex-col space-y-4 overflow-hidden rounded-lg border border-gray-200 bg-white p-4">
    <figure class="overflow-hidden rounded-lg shadow">
        <img class="h-full w-full object-cover object-center transition duration-300 hover:scale-110" src="{{ image_url($cover) }}" alt="{{ $title }}" />
    </figure>

    <article>
        <a href="{{ route('posts.show', $slug) }}"
           class="line-clamp-1 text-xl font-bold transition hover:text-blue-600">
            {{ $title }}
        </a>

        <p class="prose mt-2 line-clamp-3 text-gray-500">{{ $content }}</p>
    </article>

    <footer class="flex items-center justify-between text-sm text-gray-500">
        <div class="flex items-center">
            <x-heroicon-o-user class="mr-px h-5 w-5"/>
            <span>{{ $author }}</span>
        </div>

        <div class="flex items-center">
            <x-heroicon-o-clock class="mr-px h-5 w-5"/>
            <span>{{ $date->format('d/m/Y') }}</span>
        </div>

        <div class="flex items-center">
            <x-heroicon-o-chat-alt-2 class="mr-px h-5 w-5"/>
            <a href="{{ sprintf('%s#%s', route('posts.show', $slug), 'disqus_thread') }}">
                0 comentário
            </a>
        </div>
    </footer>
</div>

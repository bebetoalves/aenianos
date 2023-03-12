<section>
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

    {{ $posts->links('components.pagination') }}
</section>

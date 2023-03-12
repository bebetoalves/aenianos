<a href="{{ route('projects.show', $project->slug) }}" class="group relative inline-block h-full w-full overflow-hidden rounded">
    <img
        src="{{ image_url($project->miniature) }}"
        class="h-full w-full object-cover object-center transition duration-300 group-hover:scale-110"
        alt="{{ $project->title }}"
    />

    <span class="absolute inset-x-0 bottom-0 truncate bg-gray-900/50 px-2 py-4 text-center text-sm font-bold text-white backdrop-blur-sm">
        {{ $project->title }}
    </span>
</a>

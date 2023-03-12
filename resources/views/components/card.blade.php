<div {{ $attributes->merge(['class' => 'flex flex-col rounded-lg border border-gray-200 bg-white p-4']) }}>
    <div class="flex items-center">
        <div class="mr-2 rounded-lg bg-blue-600 p-1 text-white">
            {{ $icon }}
        </div>
        <span class="text-xl font-bold">{{ $title }}</span>
    </div>

    <div class="mt-6">
        {{ $slot }}
    </div>
</div>

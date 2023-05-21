<div x-data="{ open: false }" class="relative">
    <span
        @mouseover="open = true"
        @mouseleave="open = false"
        class="flex items-center rounded-md bg-{{ $color ?? 'gray' }}-50 px-2 py-1 text-xs font-medium text-{{ $color ?? 'gray' }}-600 ring-1 ring-inset ring-{{ $color ?? 'gray' }}-500/10"
    >
        @if (isset($icon))
            {{ $icon }}
        @endif

        <span>{{ $label }}</span>
    </span>

    @if (isset($tooltip))
        <span
            x-cloak
            x-transition
            x-show="open"
            class="absolute bottom-6 mb-0.5 z-10 items-center rounded-md bg-{{ $color ?? 'gray' }}-50 px-2 py-1 text-xs font-medium text-{{ $color ?? 'gray' }}-600 ring-1 ring-inset ring-{{ $color ?? 'gray' }}-500/10"
        >
            {{ $tooltip }}
        </span>
    @endif
</div>

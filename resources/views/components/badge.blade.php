<div x-data="{ open: false }" class="relative">
    <button
        @mouseover="open = true"
        @mouseleave="open = false"
        class="flex items-center rounded px-2.5 py-0.5 text-xs font-medium tracking-wide bg-{{ $color ?? 'gray' }}-100 text-{{ $color ?? 'gray' }}-800"
    >
        @if (isset($icon))
            {{ $icon }}
        @endif

        <span>{{ $label }}</span>
    </button>

    @if (isset($tooltip))
        <span
            x-cloak
            x-transition
            x-show="open"
            class="bg-{{ $color }}-100 text-{{ $color }}-800 absolute bottom-6 z-10 rounded px-2.5 py-0.5 text-center text-xs font-medium tracking-wide"
        >
            {{ $tooltip }}
        </span>
    @endif
</div>

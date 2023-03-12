<ul {{ $attributes }}>
    <li>
        <a href="{{ route('home') }}" @class(['flex items-center font-medium transition hover:text-blue-600', 'text-blue-600' => route_is('home')])>
            <x-heroicon-o-home class="mr-1 h-5 w-5" />
            Início
        </a>
    </li>

    <li>
        <a href="{{ route('posts') }}" @class(['flex items-center font-medium transition hover:text-blue-600', 'text-blue-600' => route_is('posts')])>
            <x-heroicon-o-newspaper class="mr-1 h-5 w-5" />
            Postagens
        </a>
    </li>

    <li>
        <a href="{{ route('projects') }}" @class(['flex items-center font-medium transition hover:text-blue-600', 'text-blue-600' => route_is('projects')])>
            <x-heroicon-o-film class="mr-1 h-5 w-5" />
            Projetos
        </a>
    </li>

    <li>
        <a href="{{ route('about') }}" @class(['flex items-center font-medium transition hover:text-blue-600', 'text-blue-600' => route_is('about')])>
            <x-heroicon-o-users class="mr-1 h-5 w-5" />
            Sobre
        </a>
    </li>

    <li>
        <a href="{{ route('faq') }}" @class(['flex items-center font-medium transition hover:text-blue-600', 'text-blue-600' => route_is('faq')])>
            <x-heroicon-o-information-circle class="mr-1 h-5 w-5" />
            FAQ
        </a>
    </li>
</ul>

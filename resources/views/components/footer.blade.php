<footer {{ $attributes }}>
    <div class="border-t border-gray-200 bg-white py-12 px-4">
        <div class="mx-auto flex max-w-7xl flex-wrap items-start gap-12 md:justify-between xl:gap-0">
            <div class="flex w-full flex-col xl:w-1/3">
                <x-logo />
                <span class="mt-2 leading-6 text-gray-500">Traduzindo a magia oriental há mais de 16 anos.</span>

                <ul class="mt-6 flex space-x-4">
                    <li>
                        <a href="https://www.facebook.com/aefansubber" target="_blank" class="text-gray-500 transition hover:text-gray-600">
                            <x-icons.brand-facebook class="h-6 w-6" />
                        </a>
                    </li>
                    <li>
                        <a href="#" target="_blank" class="text-gray-500 transition hover:text-gray-600">
                            <x-icons.brand-discord class="h-6 w-6" />
                        </a>
                    </li>
                    <li>
                        <a href="https://twitter.com/fansubberAE" target="_blank" class="text-gray-500 transition hover:text-gray-600">
                            <x-icons.brand-twitter class="h-6 w-6" />
                        </a>
                    </li>
                    <li>
                        <a href="https://www.infoanime.com.br/fansub?sub=2" target="_blank" class="text-gray-500 transition hover:text-gray-600">
                            <x-heroicon-o-information-circle class="h-6 w-6" />
                        </a>
                    </li>
                </ul>
            </div>

            <div class="w-3/4 md:w-1/4 xl:w-1/6 xl:flex-none">
                <h3 class="whitespace-nowrap text-xl font-bold">Postagens Populares</h3>
                <ul class="mt-2.5 flex flex-col space-y-1">
                    @foreach ($popularPosts as $post)
                        <li class="truncate text-gray-500">
                            <a href="{{ route('posts.show', $post['slug']) }}" class="whitespace-nowrap text-base leading-6 text-gray-500 transition hover:text-blue-600">
                                {{ $post['title'] }}
                            </a>
                        </li>
                    @endforeach
                </ul>
            </div>

            <div class="w-3/4 md:w-1/4 xl:w-1/6 xl:flex-none">
                <h3 class="whitespace-nowrap text-xl font-bold">Novos Projetos</h3>
                <ul class="mt-2.5 flex flex-col space-y-1">
                    @foreach ($latestProjects as $project)
                        <li class="truncate text-gray-500">
                            <a href="{{ route('projects.show', $project['slug']) }}" class="whitespace-nowrap text-base leading-6 text-gray-500 transition hover:text-blue-600">
                                {{ $project['title'] }}
                            </a>
                        </li>
                    @endforeach
                </ul>
            </div>

            <div class="w-3/4 md:w-1/4 xl:w-1/6 xl:flex-none">
                <h3 class="whitespace-nowrap text-xl font-bold">Projetos Populares</h3>
                <ul class="mt-2.5 flex flex-col space-y-1">
                    @foreach ($popularProjects as $project)
                        <li class="truncate text-gray-500">
                            <a href="{{ route('projects.show', $project['slug']) }}" class="whitespace-nowrap text-base leading-6 text-gray-500 transition hover:text-blue-600">
                                {{ $project['title'] }}
                            </a>
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>

    <div class="bg-gray-100 py-6 px-4">
        <div class="mx-auto flex max-w-7xl flex-col items-center md:flex-row md:justify-between">
            <span class="text-sm font-light tracking-wide text-gray-400">Desenvolvido com ☕ por Meteor</span>
            <span class="text-sm font-light tracking-wide text-gray-400">Aenianos Fansubber © {{ date('Y') }}</span>
        </div>
    </div>
</footer>

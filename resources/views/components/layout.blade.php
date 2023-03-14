<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" type="image/x-icon" href="{{ asset('images/favicon.ico') }}">
    <title>{{ sprintf('%s - %s', config('app.name', 'Laravel'), $title) }}</title>

    <!-- SEO -->
    @if (isset($seo)) {{ $seo }} @else {!! seo() !!} @endif

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@100;200;300;400;500;600;700;800;900&display=swap" rel="stylesheet">

    <!-- Scripts and Styles -->
    @vite(['resources/js/app.js', 'resources/css/app.css'])
    @livewireStyles
</head>
    <body x-data class="bg-gray-50 text-gray-900 selection:bg-blue-600 selection:text-white">
        <section class="flex min-h-screen flex-col">
            <x-navbar />

            <section class="px-4">
                <div class="mx-auto w-full max-w-7xl">
                    @if (isset($header))
                        {{ $header }}
                    @endif
                </div>
            </section>

            <main class="my-12 px-4">
                <div class="mx-auto grid max-w-7xl grid-cols-1 grid-rows-1 xl:grid-cols-4 xl:gap-4">
                    <div class="col-span-3">
                        {{ $slot }}
                    </div>

                    <div class="mt-12 xl:mt-0">
                        <x-sidebar />
                    </div>
                </div>
            </main>

            <x-footer class="mt-auto" />

             <livewire:search />
        </section>

        @livewireScripts
        <script id="dsq-count-scr" src="//{{ config('disqus.user') }}.disqus.com/count.js" async></script>
    </body>
</html>

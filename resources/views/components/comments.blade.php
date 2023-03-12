<x-card {{ $attributes }}>
    <x-slot:title>Comentários</x-slot:title>
    <x-slot:icon>
        <x-heroicon-o-chat-alt-2 class="h-5 w-5" />
    </x-slot:icon>

    <div id="disqus_thread"></div>

    <script>
        const disqus_config = function () {
            this.page.url = '{{ url()->current() }}';
            this.page.identifier = '{{ request()->path() }}'
        };

        (function() {
            const doc = document, script = doc.createElement('script');
            script.src = '//{{ config('disqus.user') }}.disqus.com/embed.js';
            script.setAttribute('data-timestamp', +new Date());
            (doc.head || doc.body).appendChild(script);
        })();
    </script>

    <noscript>Por favor, habilite o JavaScript para ver os comentários oferecidos pelo Disqus.</noscript>
</x-card>

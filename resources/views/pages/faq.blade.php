<x-layout>
    <x-slot:title>Perguntas Frequentes</x-slot:title>

    <x-card>
        <x-slot:title>Perguntas Frequentes</x-slot:title>
        <x-slot:icon>
            <x-heroicon-o-information-circle class="h-5 w-5" />
        </x-slot:icon>

        <p class="text-justify text-gray-500">
            Criamos esta seção para você poder encontrar as respostas para as dúvidas mais comuns que os nossos visitantes têm.
            Antes de entrar em contato conosco, sugerimos que dê uma olhada nesta página para ver se a sua pergunta já foi respondida.
        </p>

        <section x-data="{ open: null }" class="mt-6">
            @forelse ($faqs as $faq)
                <div class="mb-3">
                    <button @click="open !== {{ $faq->getKey() }} ? open = {{ $faq->getKey() }} : open = null" class="flex w-full justify-between rounded-lg border border-gray-200 p-4 font-medium transition hover:bg-gray-500/5">
                        <span>{{ $faq->question }}</span>
                        <x-heroicon-o-chevron-up x-bind:class="open || 'rotate-180'" class="h-5 w-5 text-gray-500" />
                    </button>

                    <div
                        x-cloak
                        x-show="open === {{ $faq->getKey() }}"
                        x-transition.in.opacity.duration.500ms
                        class="mt-2 rounded-lg border border-gray-200 bg-gray-50 p-4 text-gray-500"
                    >
                        <x-markdown class="prose max-w-none">
                            {{ $faq->answer }}
                        </x-markdown>
                    </div>
                </div>
            @empty
                <span class="mt-6 block text-gray-500">Não há perguntas frequentes no momento!</span>
            @endforelse
        </section>
    </x-card>
</x-layout>

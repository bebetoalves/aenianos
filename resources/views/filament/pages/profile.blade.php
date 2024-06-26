<x-filament::page>
    <form wire:submit.prevent="submit" class="space-y-6">
        {{ $this->form }}

        <div class="flex flex-wrap items-center justify-start gap-4">
            <x-filament::button type="submit">
                {{ __('filament::resources/pages/edit-record.form.actions.save.label') }}
            </x-filament::button>
        </div>
    </form>
</x-filament::page>

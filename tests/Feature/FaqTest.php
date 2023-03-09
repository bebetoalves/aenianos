<?php

namespace Tests\Feature;

use App\Filament\Resources\FaqResource;
use App\Models\Faq;
use Filament\Pages\Actions\CreateAction;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\EditAction;
use Illuminate\Support\Str;
use Livewire\Livewire;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class FaqTest extends TestCase
{
    public static function provideValidation(): array
    {
        return [
            'required fields' => [
                'input' => [
                    'question' => '',
                    'answer' => '',
                ],
                'errors' => [
                    'question' => 'required',
                    'answer' => 'required',
                ],
            ],
            'max length question' => [
                'input' => [
                    'question' => Str::random(101),
                ],
                'errors' => [
                    'question' => 'max',
                ],
            ],
        ];
    }

    #[Test]
    public function canRenderPage(): void
    {
        $this->get(FaqResource::getUrl())->assertSuccessful();
    }

    #[Test]
    public function canRenderColumns(): void
    {
        $data = Faq::factory(10)->create();

        Livewire::test(FaqResource\Pages\ManageFaqs::class)
            ->assertCanSeeTableRecords($data)
            ->assertCanRenderTableColumn('question')
            ->assertCanRenderTableColumn('answer')
            ->assertCanRenderTableColumn('created_at')
            ->assertCanRenderTableColumn('updated_at');
    }

    #[Test]
    public function canCreate(): void
    {
        $data = Faq::factory()->makeOne();

        Livewire::test(FaqResource\Pages\ManageFaqs::class)
            ->callPageAction(CreateAction::class, [
                'question' => $data->question,
                'answer' => $data->answer,
            ]);

        self::assertDatabaseHas(Faq::class, [
            'question' => $data->question,
            'answer' => $data->answer,
        ]);
    }

    #[Test]
    public function canEdit()
    {
        $record = Faq::factory()->createOne();
        $data = Faq::factory()->makeOne();

        Livewire::test(FaqResource\Pages\ManageFaqs::class)
            ->callTableAction(EditAction::class, $record, [
                'question' => $data->question,
                'answer' => $data->answer,
            ]);

        $record->refresh();
        self::assertEquals($data->question, $record->question);
        self::assertEquals($data->answer, $record->answer);
    }

    #[Test]
    public function canDelete()
    {
        $record = Faq::factory()->createOne();

        Livewire::test(FaqResource\Pages\ManageFaqs::class)
            ->callTableAction(DeleteAction::class, $record)
            ->assertHasNoTableActionErrors();

        self::assertModelMissing($record);
    }

    #[Test]
    #[DataProvider(methodName: 'provideValidation')]
    public function editValidation(array $input, array $errors)
    {
        $data = Faq::factory()->makeOne();
        $record = Faq::factory()->createOne();

        Livewire::test(FaqResource\Pages\ManageFaqs::class)
            ->callTableAction(EditAction::class, $record, array_merge($data->toArray(), $input))
            ->assertHasTableActionErrors($errors);
    }

    #[Test]
    #[DataProvider(methodName: 'provideValidation')]
    public function createValidation(array $input, array $errors): void
    {
        $data = Faq::factory()->makeOne();

        Livewire::test(FaqResource\Pages\ManageFaqs::class)
            ->callPageAction(CreateAction::class, array_merge($data->toArray(), $input))
            ->assertHasPageActionErrors($errors);
    }
}

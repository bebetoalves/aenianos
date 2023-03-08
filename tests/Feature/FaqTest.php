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
use Tests\TestCase;

class FaqTest extends TestCase
{
    public function testCanRenderPage(): void
    {
        $this->get(FaqResource::getUrl())->assertSuccessful();
    }

    public function testCanRenderColumns(): void
    {
        $data = Faq::factory(10)->create();

        Livewire::test(FaqResource\Pages\ManageFaqs::class)
            ->assertCanSeeTableRecords($data)
            ->assertCanRenderTableColumn('question')
            ->assertCanRenderTableColumn('answer')
            ->assertCanRenderTableColumn('created_at')
            ->assertCanRenderTableColumn('updated_at');
    }

    public function testCreate(): void
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

    #[DataProvider(methodName: 'provideValidation')]
    public function testCreateValidation(array $input, array $errors): void
    {
        $data = Faq::factory()->makeOne();

        Livewire::test(FaqResource\Pages\ManageFaqs::class)
            ->callPageAction(CreateAction::class, array_merge($data->toArray(), $input))
            ->assertHasPageActionErrors($errors);
    }

    public function testEdit()
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

    #[DataProvider(methodName: 'provideValidation')]
    public function testEditValidation(array $input, array $errors)
    {
        $data = Faq::factory()->makeOne();
        $record = Faq::factory()->createOne();

        Livewire::test(FaqResource\Pages\ManageFaqs::class)
            ->callTableAction(EditAction::class, $record, array_merge($data->toArray(), $input))
            ->assertHasTableActionErrors($errors);
    }

    public function testDelete()
    {
        $record = Faq::factory()->createOne();

        Livewire::test(FaqResource\Pages\ManageFaqs::class)
            ->callTableAction(DeleteAction::class, $record)
            ->assertHasNoTableActionErrors();

        self::assertModelMissing($record);
    }

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
}

<?php

namespace Tests\Feature;

use App\Filament\Resources\HighlightResource;
use App\Models\Highlight;
use Filament\Pages\Actions\CreateAction;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\EditAction;
use Livewire\Livewire;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class HighlightTest extends TestCase
{
    public static function provideValidation(): array
    {
        return [
            'required values' => [
                'input' => [
                    'project_id' => null,
                    'use_project_cover' => true,
                ],
                'errors' => [
                    'project_id' => 'required',
                ],
            ],
            'cover is required if use project cover is false' => [
                'input' => [
                    'cover' => null,
                    'use_project_cover' => false,
                ],
                'errors' => [
                    'cover' => 'required',
                ],
            ],
        ];
    }

    #[Test]
    public function canRenderPage(): void
    {
        $this->get(HighlightResource::getUrl())->assertSuccessful();
    }

    #[Test]
    public function canRenderColumns(): void
    {
        $data = Highlight::factory(10)->create();

        Livewire::test(HighlightResource\Pages\ManageHighlights::class)
            ->assertCanSeeTableRecords($data)
            ->assertCanRenderTableColumn('project.title')
            ->assertCanRenderTableColumn('cover')
            ->assertCanRenderTableColumn('created_at')
            ->assertCanRenderTableColumn('updated_at');
    }

    #[Test]
    public function canCreate(): void
    {
        $data = Highlight::factory()->makeOne(['cover' => 'example.png']);

        Livewire::test(HighlightResource\Pages\ManageHighlights::class)
            ->callPageAction(CreateAction::class, [
                'project_id' => $data->project_id,
                'cover' => [$data->cover],
                'use_project_cover' => false,
            ]);

        self::assertDatabaseHas(Highlight::class, [
            'project_id' => $data->project_id,
            'cover' => $data->cover,
        ]);
    }

    #[Test]
    public function canCreateWithoutCover()
    {
        $data = Highlight::factory()->makeOne();

        Livewire::test(HighlightResource\Pages\ManageHighlights::class)
            ->callPageAction(CreateAction::class, [
                'project_id' => $data->project_id,
                'use_project_cover' => true,
            ]);

        self::assertDatabaseHas(Highlight::class, [
            'project_id' => $data->project_id,
            'cover' => null,
        ]);
    }

    #[Test]
    public function canEdit()
    {
        $record = Highlight::factory()->createOne();
        $data = Highlight::factory()->makeOne(['cover' => 'example.png']);

        Livewire::test(HighlightResource\Pages\ManageHighlights::class)
            ->callTableAction(EditAction::class, $record, [
                'project_id' => $data->project_id,
                'cover' => [$data->cover],
                'use_project_cover' => false,
            ])
            ->assertHasNoTableActionErrors();

        $record->refresh();
        self::assertEquals($data->project_id, $record->project_id);
        self::assertEquals($data->cover, $record->cover);
    }

    #[Test]
    public function editCanUseProjectCover()
    {
        $record = Highlight::factory()->createOne(['cover' => 'example.png']);
        $data = Highlight::factory()->makeOne();

        Livewire::test(HighlightResource\Pages\ManageHighlights::class)
            ->callTableAction(EditAction::class, $record, [
                'project_id' => $data->project_id,
                'use_project_cover' => true,
            ]);

        $record->refresh();
        self::assertEquals($data->project_id, $record->project_id);
        self::assertNull($record->cover);
    }

    #[Test]
    public function canDelete()
    {
        $record = Highlight::factory()->createOne();

        Livewire::test(HighlightResource\Pages\ManageHighlights::class)
            ->callTableAction(DeleteAction::class, $record)
            ->assertHasNoTableActionErrors();

        self::assertModelMissing($record);
    }

    #[Test]
    #[DataProvider(methodName: 'provideValidation')]
    public function createValidation(array $input, array $errors): void
    {
        $data = Highlight::factory()->makeOne();

        Livewire::test(HighlightResource\Pages\ManageHighlights::class)
            ->callPageAction(CreateAction::class, array_merge($data->toArray(), $input))
            ->assertHasPageActionErrors($errors);
    }

    #[Test]
    #[DataProvider(methodName: 'provideValidation')]
    public function editValidation(array $input, array $errors)
    {
        $data = Highlight::factory()->makeOne();
        $record = Highlight::factory()->createOne();

        Livewire::test(HighlightResource\Pages\ManageHighlights::class)
            ->callTableAction(EditAction::class, $record, array_merge($data->toArray(), $input))
            ->assertHasTableActionErrors($errors);
    }
}

<?php

namespace Tests\Feature;

use App\Filament\Resources\HighlightResource;
use App\Models\Highlight;
use Filament\Pages\Actions\CreateAction;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\EditAction;
use Livewire\Livewire;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

class HighlightTest extends TestCase
{
    public function testCanRenderPage(): void
    {
        $this->get(HighlightResource::getUrl())->assertSuccessful();
    }

    public function testCanRenderColumns(): void
    {
        $data = Highlight::factory(10)->create();

        Livewire::test(HighlightResource\Pages\ManageHighlights::class)
            ->assertCanSeeTableRecords($data)
            ->assertCanRenderTableColumn('project.title')
            ->assertCanRenderTableColumn('cover')
            ->assertCanRenderTableColumn('created_at')
            ->assertCanRenderTableColumn('updated_at');
    }

    public function testCreate(): void
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

    public function testCanCreateWithoutCover()
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

    #[DataProvider(methodName: 'provideValidation')]
    public function testCreateValidation(array $input, array $errors): void
    {
        $data = Highlight::factory()->makeOne();

        Livewire::test(HighlightResource\Pages\ManageHighlights::class)
            ->callPageAction(CreateAction::class, array_merge($data->toArray(), $input))
            ->assertHasPageActionErrors($errors);
    }

    public function testEdit()
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

    public function testEditCanUseProjectCover()
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

    #[DataProvider(methodName: 'provideValidation')]
    public function testEditValidation(array $input, array $errors)
    {
        $data = Highlight::factory()->makeOne();
        $record = Highlight::factory()->createOne();

        Livewire::test(HighlightResource\Pages\ManageHighlights::class)
            ->callTableAction(EditAction::class, $record, array_merge($data->toArray(), $input))
            ->assertHasTableActionErrors($errors);
    }

    public function testDelete()
    {
        $record = Highlight::factory()->createOne();

        Livewire::test(HighlightResource\Pages\ManageHighlights::class)
            ->callTableAction(DeleteAction::class, $record)
            ->assertHasNoTableActionErrors();

        self::assertModelMissing($record);
    }

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
}

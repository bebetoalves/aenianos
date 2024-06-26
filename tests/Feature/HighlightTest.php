<?php

namespace Tests\Feature;

use App\Filament\Resources\HighlightResource;
use App\Filament\Resources\HighlightResource\Pages\ManageHighlights;
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
            'required fields' => [
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
    public function canRenderList(): void
    {
        self::get(HighlightResource::getUrl())->assertSuccessful();
    }

    #[Test]
    public function canRenderColumns(): void
    {
        $data = Highlight::factory(10)->create();

        Livewire::test(ManageHighlights::class)
            ->assertCanSeeTableRecords($data)
            ->assertCanRenderTableColumn('project.title')
            ->assertCanRenderTableColumn('cover')
            ->assertCanRenderTableColumn('created_at')
            ->assertCanRenderTableColumn('updated_at');
    }

    #[Test]
    public function canCreate(): void
    {
        $data = Highlight::factory()->make();

        Livewire::test(ManageHighlights::class)
            ->callPageAction(CreateAction::class, [
                'project_id' => $data->project_id,
                'cover' => [$data->cover],
                'use_project_cover' => false,
            ])
            ->assertHasNoPageActionErrors();

        self::assertDatabaseHas(Highlight::class, [
            'project_id' => $data->project_id,
            'cover' => $data->cover,
        ]);
    }

    #[Test]
    public function canCreateUsingProjectCover(): void
    {
        $data = Highlight::factory()->make();

        Livewire::test(ManageHighlights::class)
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
    public function canEdit(): void
    {
        $record = Highlight::factory()->create();
        $data = Highlight::factory()->make();

        Livewire::test(ManageHighlights::class)
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
    public function canEditUseProjectCover(): void
    {
        $record = Highlight::factory()->create(['cover' => placekitten(1280, 720)]);
        $data = Highlight::factory()->make();

        Livewire::test(ManageHighlights::class)
            ->callTableAction(EditAction::class, $record, [
                'project_id' => $data->project_id,
                'use_project_cover' => true,
            ])
            ->assertHasNoTableActionErrors();

        $record->refresh();

        self::assertEquals($data->project_id, $record->project_id);
        self::assertNull($record->cover);
    }

    #[Test]
    public function canDelete(): void
    {
        $record = Highlight::factory()->create();

        Livewire::test(ManageHighlights::class)
            ->callTableAction(DeleteAction::class, $record)
            ->assertHasNoTableActionErrors();

        self::assertModelMissing($record);
    }

    #[Test, DataProvider(methodName: 'provideValidation')]
    public function canValidateCreate(array $input, array $errors): void
    {
        $data = Highlight::factory()->make();

        Livewire::test(ManageHighlights::class)
            ->callPageAction(CreateAction::class, array_merge($data->toArray(), $input))
            ->assertHasPageActionErrors($errors);
    }

    #[Test, DataProvider(methodName: 'provideValidation')]
    public function canValidateEdit(array $input, array $errors): void
    {
        $data = Highlight::factory()->make();
        $record = Highlight::factory()->create();

        Livewire::test(ManageHighlights::class)
            ->callTableAction(EditAction::class, $record, array_merge($data->toArray(), $input))
            ->assertHasTableActionErrors($errors);
    }
}

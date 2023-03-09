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

    /**
     * @test
     */
    public function canRenderPage(): void
    {
        $this->get(HighlightResource::getUrl())->assertSuccessful();
    }

    /**
     * @test
     */
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

    /**
     * @test
     */
    public function create(): void
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

    /**
     * @test
     */
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

    #[DataProvider(methodName: 'provideValidation')]
    /**
     * @test
     */
    public function createValidation(array $input, array $errors): void
    {
        $data = Highlight::factory()->makeOne();

        Livewire::test(HighlightResource\Pages\ManageHighlights::class)
            ->callPageAction(CreateAction::class, array_merge($data->toArray(), $input))
            ->assertHasPageActionErrors($errors);
    }

    /**
     * @test
     */
    public function edit()
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

    /**
     * @test
     */
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

    #[DataProvider(methodName: 'provideValidation')]
    /**
     * @test
     */
    public function editValidation(array $input, array $errors)
    {
        $data = Highlight::factory()->makeOne();
        $record = Highlight::factory()->createOne();

        Livewire::test(HighlightResource\Pages\ManageHighlights::class)
            ->callTableAction(EditAction::class, $record, array_merge($data->toArray(), $input))
            ->assertHasTableActionErrors($errors);
    }

    /**
     * @test
     */
    public function delete()
    {
        $record = Highlight::factory()->createOne();

        Livewire::test(HighlightResource\Pages\ManageHighlights::class)
            ->callTableAction(DeleteAction::class, $record)
            ->assertHasNoTableActionErrors();

        self::assertModelMissing($record);
    }
}

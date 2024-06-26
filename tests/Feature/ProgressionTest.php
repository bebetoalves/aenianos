<?php

namespace Tests\Feature;

use App\Enums\State;
use App\Filament\Resources\ProgressionResource;
use App\Filament\Resources\ProgressionResource\Pages\ManageProgressions;
use App\Models\Progression;
use Filament\Pages\Actions\CreateAction;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\EditAction;
use Illuminate\Support\Str;
use Livewire\Livewire;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class ProgressionTest extends TestCase
{
    public static function provideValidation(): array
    {
        return [
            'required fields' => [
                'input' => [
                    'media' => null,
                    'states' => null,
                    'project_id' => null,
                ],
                'errors' => [
                    'media' => 'required',
                    'states' => 'required',
                    'project_id' => 'required',
                ],
            ],
            'max length' => [
                'input' => [
                    'media' => Str::random(),
                    'states' => State::getValues(),
                ],
                'errors' => [
                    'media' => 'max',
                    'states' => 'max',
                ],
            ],
            'unique fields' => [
                'input' => [
                    'project_id' => fn () => Progression::factory()->create()->project_id,
                ],
                'errors' => [
                    'project_id' => 'unique',
                ],
            ],
        ];
    }

    #[Test]
    public function canRenderList(): void
    {
        self::get(ProgressionResource::getUrl())->assertSuccessful();
    }

    #[Test]
    public function canRenderColumns(): void
    {
        $data = Progression::factory(10)->create();

        Livewire::test(ManageProgressions::class)
            ->assertCanSeeTableRecords($data)
            ->assertCanRenderTableColumn('media')
            ->assertCanRenderTableColumn('project.title')
            ->assertCanRenderTableColumn('states')
            ->assertCanRenderTableColumn('created_at')
            ->assertCanRenderTableColumn('updated_at');
    }

    #[Test]
    public function canCreate(): void
    {
        $data = Progression::factory()->make();

        Livewire::test(ManageProgressions::class)
            ->callPageAction(CreateAction::class, [
                'media' => $data->media,
                'states' => $data->states->toArray(),
                'project_id' => $data->project_id,
            ])
            ->assertHasNoPageActionErrors();

        self::assertDatabaseHas(Progression::class, [
            'media' => $data->media,
            'states' => json_encode($data->states),
            'project_id' => $data->project_id,
        ]);
    }

    #[Test]
    public function canEdit(): void
    {
        $record = Progression::factory()->create();
        $data = Progression::factory()->make();

        Livewire::test(ManageProgressions::class)
            ->callTableAction(EditAction::class, $record, [
                'media' => $data->media,
                'states' => $data->states->toArray(),
                'project_id' => $data->project_id,
            ])
            ->assertHasNoTableActionErrors();

        $record->refresh();

        self::assertEquals($data->media, $record->media);
        self::assertEquals($data->states, $record->states);
        self::assertEquals($data->project_id, $record->project_id);
    }

    #[Test]
    public function canDelete(): void
    {
        $record = Progression::factory()->create();

        Livewire::test(ManageProgressions::class)
            ->callTableAction(DeleteAction::class, $record)
            ->assertHasNoTableActionErrors();

        self::assertModelMissing($record);
    }

    #[Test]
    #[DataProvider(methodName: 'provideValidation')]
    public function canValidateCreate(array $input, array $errors): void
    {
        $data = Progression::factory()->make();

        $input = self::executeCallables($input);

        Livewire::test(ManageProgressions::class)
            ->callPageAction(CreateAction::class, array_merge($data->toArray(), $input))
            ->assertHasPageActionErrors($errors);
    }

    #[Test]
    #[DataProvider(methodName: 'provideValidation')]
    public function canValidateEdit(array $input, array $errors): void
    {
        $data = Progression::factory()->make();
        $record = Progression::factory()->create();

        $input = self::executeCallables($input);

        Livewire::test(ManageProgressions::class)
            ->callTableAction(EditAction::class, $record, array_merge($data->toArray(), $input))
            ->assertHasTableActionErrors($errors);
    }
}

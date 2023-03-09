<?php

namespace Tests\Feature;

use App\Filament\Resources\GenreResource;
use App\Models\Genre;
use App\Models\Project;
use Filament\Pages\Actions\CreateAction;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\EditAction;
use Illuminate\Support\Str;
use Livewire\Livewire;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class GenreTest extends TestCase
{
    public static function provideValidation(): array
    {
        return [
            'required fields' => [
                'input' => ['name' => null],
                'errors' => ['name' => 'required'],
            ],
            'max length' => [
                'input' => ['name' => Str::random(31)],
                'errors' => ['name' => 'max'],
            ],
            'unique fields' => [
                'input' => ['name' => fn () => Genre::factory()->createOne()->name],
                'errors' => ['name' => 'unique'],
            ],
        ];
    }

    #[Test]
    public function canRenderList(): void
    {
        $this->get(GenreResource::getUrl())->assertSuccessful();
    }

    #[Test]
    public function canRenderColumns(): void
    {
        $data = Genre::factory(10)->create();

        Livewire::test(GenreResource\Pages\ManageGenres::class)
            ->assertCanSeeTableRecords($data)
            ->assertCanRenderTableColumn('name')
            ->assertCanRenderTableColumn('projects_count')
            ->assertCanRenderTableColumn('created_at')
            ->assertCanRenderTableColumn('updated_at');
    }

    #[Test]
    public function canCreate(): void
    {
        $data = Genre::factory()->makeOne();

        Livewire::test(GenreResource\Pages\ManageGenres::class)
            ->callPageAction(CreateAction::class, [
                'name' => $data->name,
            ])
            ->assertHasNoPageActionErrors();

        self::assertDatabaseHas(Genre::class, ['name' => $data->name]);
    }

    #[Test]
    public function canEdit()
    {
        $record = Genre::factory()->createOne();
        $data = Genre::factory()->makeOne();

        Livewire::test(GenreResource\Pages\ManageGenres::class)
            ->callTableAction(EditAction::class, $record, ['name' => $data->name])
            ->assertHasNoTableActionErrors();

        $record->refresh();

        self::assertEquals($data->name, $record->name);
    }

    #[Test]
    public function canDelete()
    {
        $record = Genre::factory()->createOne();

        Livewire::test(GenreResource\Pages\ManageGenres::class)
            ->callTableAction(DeleteAction::class, $record)
            ->assertHasNoTableActionErrors();

        self::assertModelMissing($record);
    }

    #[Test]
    public function cannotDeleteIfHasProjects()
    {
        $record = Genre::factory()->createOne();

        $project = Project::factory()->createOne();
        $project->genres()->attach($record->getKey());

        Livewire::test(GenreResource\Pages\ManageGenres::class)
            ->callTableAction(DeleteAction::class, $record)
            ->assertNotified();

        self::assertModelExists($record);
        self::assertModelExists($project);
    }

    #[Test, DataProvider(methodName: 'provideValidation')]
    public function canValidateCreate(array $input, array $errors): void
    {
        $input = $this->executeCallables($input);

        Livewire::test(GenreResource\Pages\ManageGenres::class)
            ->callPageAction(CreateAction::class, $input)
            ->assertHasPageActionErrors($errors);
    }

    #[Test, DataProvider(methodName: 'provideValidation')]
    public function canValidateEdit(array $input, array $errors)
    {
        $input = $this->executeCallables($input);
        $record = Genre::factory()->createOne();

        Livewire::test(GenreResource\Pages\ManageGenres::class)
            ->callTableAction(EditAction::class, $record, $input)
            ->assertHasTableActionErrors($errors);
    }
}

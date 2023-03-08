<?php

namespace Tests\Feature;

use App\Filament\Resources\GenreResource;
use App\Models\Genre;
use App\Models\Project;
use Closure;
use Filament\Pages\Actions\CreateAction;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\EditAction;
use Illuminate\Support\Str;
use Livewire\Livewire;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

class GenreTest extends TestCase
{
    public function testCanRenderPage(): void
    {
        $this->get(GenreResource::getUrl())->assertSuccessful();
    }

    public function testCanRenderColumns(): void
    {
        $data = Genre::factory(10)->create();

        Livewire::test(GenreResource\Pages\ManageGenres::class)
            ->assertCanSeeTableRecords($data)
            ->assertCanRenderTableColumn('name')
            ->assertCanRenderTableColumn('projects_count')
            ->assertCanRenderTableColumn('created_at')
            ->assertCanRenderTableColumn('updated_at');
    }

    public function testCreate(): void
    {
        $data = Genre::factory()->makeOne();

        Livewire::test(GenreResource\Pages\ManageGenres::class)
            ->callPageAction(CreateAction::class, [
                'name' => $data->name,
            ]);

        self::assertDatabaseHas(Genre::class, ['name' => $data->name]);
    }

    #[DataProvider(methodName: 'provideValidation')]
    public function testCreateValidation(Closure $closure, string $error): void
    {
        $input = $closure();

        Livewire::test(GenreResource\Pages\ManageGenres::class)
            ->callPageAction(CreateAction::class, [
                'name' => $input,
            ])
            ->assertHasPageActionErrors(['name' => $error]);
    }

    public function testEdit()
    {
        $record = Genre::factory()->createOne();
        $data = Genre::factory()->makeOne();

        Livewire::test(GenreResource\Pages\ManageGenres::class)
            ->callTableAction(EditAction::class, $record, ['name' => $data->name]);

        $record->refresh();
        self::assertEquals($data->name, $record->name);
    }

    #[DataProvider(methodName: 'provideValidation')]
    public function testEditValidation(Closure $closure, string $error)
    {
        $input = $closure();
        $record = Genre::factory()->createOne();

        Livewire::test(GenreResource\Pages\ManageGenres::class)
            ->callTableAction(EditAction::class, $record, ['name' => $input])
            ->assertHasTableActionErrors(['name' => $error]);
    }

    public function testDelete()
    {
        $record = Genre::factory()->createOne();

        Livewire::test(GenreResource\Pages\ManageGenres::class)
            ->callTableAction(DeleteAction::class, $record)
            ->assertHasNoTableActionErrors();

        self::assertModelMissing($record);
    }

    public function testCannotDeleteIfHasProjects()
    {
        $record = Genre::factory()->createOne();

        $project = Project::factory()->createOne();
        $project->genres()->attach($record->id);

        Livewire::test(GenreResource\Pages\ManageGenres::class)
            ->callTableAction(DeleteAction::class, $record)
            ->assertNotified();

        self::assertModelExists($record);
        self::assertModelExists($project);
    }

    public static function provideValidation(): array
    {
        return [
            'required name' => [fn () => '', 'required'],
            'max length name' => [fn () => Str::random(31), 'max'],
            'unique name' => [fn () => Genre::factory()->createOne()->name, 'unique'],
        ];
    }
}

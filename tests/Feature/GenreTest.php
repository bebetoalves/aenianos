<?php

namespace Tests\Feature;

use App\Filament\Resources\GenreResource;
use App\Filament\Resources\GenreResource\Pages\ManageGenres;
use App\Models\Genre;
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
                'input' => ['name' => fn () => Genre::factory()->create()->name],
                'errors' => ['name' => 'unique'],
            ],
        ];
    }

    #[Test]
    public function canRenderList(): void
    {
        self::get(GenreResource::getUrl())->assertSuccessful();
    }

    #[Test]
    public function canRenderColumns(): void
    {
        $data = Genre::factory(10)->create();

        Livewire::test(ManageGenres::class)
            ->assertCanSeeTableRecords($data)
            ->assertCanRenderTableColumn('name')
            ->assertCanRenderTableColumn('projects_count')
            ->assertCanRenderTableColumn('created_at')
            ->assertCanRenderTableColumn('updated_at');
    }

    #[Test]
    public function canCreate(): void
    {
        $data = Genre::factory()->make();

        Livewire::test(ManageGenres::class)
            ->callPageAction(CreateAction::class, [
                'name' => $data->name,
            ])
            ->assertHasNoPageActionErrors();

        self::assertDatabaseHas(Genre::class, ['name' => $data->name]);
    }

    #[Test]
    public function canEdit(): void
    {
        $record = Genre::factory()->create();
        $data = Genre::factory()->make();

        Livewire::test(ManageGenres::class)
            ->callTableAction(EditAction::class, $record, ['name' => $data->name])
            ->assertHasNoTableActionErrors();

        $record->refresh();

        self::assertEquals($data->name, $record->name);
    }

    #[Test]
    public function canDelete(): void
    {
        $record = Genre::factory()->create();

        Livewire::test(ManageGenres::class)
            ->callTableAction(DeleteAction::class, $record)
            ->assertHasNoTableActionErrors();

        self::assertModelMissing($record);
    }

    #[Test, DataProvider(methodName: 'provideValidation')]
    public function canValidateCreate(array $input, array $errors): void
    {
        $input = self::executeCallables($input);

        Livewire::test(ManageGenres::class)
            ->callPageAction(CreateAction::class, $input)
            ->assertHasPageActionErrors($errors);
    }

    #[Test, DataProvider(methodName: 'provideValidation')]
    public function canValidateEdit(array $input, array $errors): void
    {
        $input = self::executeCallables($input);
        $record = Genre::factory()->create();

        Livewire::test(ManageGenres::class)
            ->callTableAction(EditAction::class, $record, $input)
            ->assertHasTableActionErrors($errors);
    }
}

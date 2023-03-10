<?php

namespace Tests\Feature;

use App\Filament\Resources\ProjectResource;
use App\Filament\Resources\ProjectResource\Pages\CreateProject;
use App\Filament\Resources\ProjectResource\Pages\EditProject;
use App\Filament\Resources\ProjectResource\Pages\ListProjects;
use App\Models\Genre;
use App\Models\Project;
use Filament\Pages\Actions\DeleteAction;
use Illuminate\Http\Testing\File;
use Illuminate\Support\Str;
use Livewire\Livewire;
use Livewire\TemporaryUploadedFile;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class ProjectTest extends TestCase
{
    private File $uploadedFile;

    protected function setUp(): void
    {
        parent::setUp();

        $this->uploadedFile = TemporaryUploadedFile::fake()->image(Str::random());
    }

    public static function provideValidation(): array
    {
        return [
            'required fields' => [
                'input' => [
                    'title' => null,
                    'synopsis' => null,
                    'episodes' => null,
                    'year' => null,
                    'season' => null,
                    'category' => null,
                    'miniature' => null,
                    'cover' => null,
                    'genres' => null,
                ],
                'errors' => [
                    'title' => 'required',
                    'synopsis' => 'required',
                    'episodes' => 'required',
                    'year' => 'required',
                    'season' => 'required',
                    'category' => 'required',
                    'miniature' => 'required',
                    'cover' => 'required',
                    'genres' => 'required',
                ],
            ],
            'max length' => [
                'input' => [
                    'title' => Str::random(31),
                    'alternative_title' => Str::random(31),
                    'episodes' => Str::random(11),
                    'synopsis' => Str::random(561),
                    'year' => Str::random(5),
                    'genres' => fn () => Genre::factory(4)->create()->pluck('id')->toArray(),
                    'related_project' => fn () => Project::factory(6)->create()->pluck('id')->toArray(),
                ],
                'errors' => [
                    'title' => 'max',
                    'alternative_title' => 'max',
                    'episodes' => 'max',
                    'synopsis' => 'max',
                    'year' => 'max',
                    'genres' => 'max',
                    'related_project' => 'max',
                ],
            ],
            'unique fields' => [
                'input' => [
                    'title' => fn () => Project::factory()->create()->title,
                ],
                'errors' => [
                    'title' => 'unique',
                ],
            ],
        ];
    }

    #[Test]
    public function canRenderList(): void
    {
        self::get(ProjectResource::getUrl())->assertSuccessful();
    }

    #[Test]
    public function canRenderColumns(): void
    {
        $data = Project::factory(10)->create();

        Livewire::test(ListProjects::class)
            ->assertCanSeeTableRecords($data)
            ->assertCanRenderTableColumn('title')
            ->assertCanRenderTableColumn('links_count')
            ->assertCanRenderTableColumn('category')
            ->assertCanRenderTableColumn('created_at')
            ->assertCanRenderTableColumn('updated_at');
    }

    #[Test]
    public function canRenderCreate(): void
    {
        self::get(ProjectResource::getUrl('create'))->assertSuccessful();
    }

    #[Test]
    public function canCreate(): void
    {
        $data = Project::factory()->make();
        $genres = Genre::factory(3)->create()->pluck('id')->toArray();
        $relatedProjects = Project::factory(5)->create()->pluck('id')->toArray();

        Livewire::test(CreateProject::class)
            ->fillForm([
                'title' => $data->title,
                'alternative_title' => $data->alternative_title,
                'synopsis' => $data->synopsis,
                'episodes' => $data->episodes,
                'year' => $data->year,
                'season' => $data->season,
                'category' => $data->category,
                'miniature' => $this->uploadedFile,
                'cover' => $this->uploadedFile,
                'genres' => $genres,
                'related_project' => $relatedProjects,
            ])
            ->call('create')
            ->assertHasNoFormErrors();

        self::assertDatabaseHas(Project::class, [
            'title' => $data->title,
            'alternative_title' => $data->alternative_title,
            'synopsis' => $data->synopsis,
            'episodes' => $data->episodes,
            'year' => $data->year,
            'season' => $data->season,
            'category' => $data->category,
        ]);
    }

    #[Test]
    public function canRenderEdit(): void
    {
        $data = Project::factory()->create();

        self::get(ProjectResource::getUrl('edit', ['record' => $data]))
            ->assertSuccessful();
    }

    #[Test]
    public function canEdit(): void
    {
        $record = Project::factory()->create();
        $data = Project::factory()->make();

        $genres = Genre::factory(3)->create()->pluck('id')->toArray();
        $relatedProjects = Project::factory(5)->create()->pluck('id')->toArray();

        Livewire::test(EditProject::class, ['record' => $record->slug])
            ->fillForm([
                'title' => $data->title,
                'alternative_title' => $data->alternative_title,
                'synopsis' => $data->synopsis,
                'episodes' => $data->episodes,
                'year' => $data->year,
                'season' => $data->season,
                'category' => $data->category,
                'miniature' => $this->uploadedFile,
                'cover' => $this->uploadedFile,
                'genres' => $genres,
                'related_project' => $relatedProjects,
            ])
            ->call('save')
            ->assertHasNoFormErrors();

        $record->refresh();

        self::assertEquals($data->title, $record->title);
        self::assertEquals($data->alternative_title, $record->alternative_title);
        self::assertEquals($data->synopsis, $record->synopsis);
        self::assertEquals($data->episodes, $record->episodes);
        self::assertEquals($data->year, $record->year);
        self::assertEquals($data->season, $record->season);
        self::assertEquals($data->category, $record->category);
    }

    #[Test]
    public function canDelete(): void
    {
        $record = Project::factory()->create();

        Livewire::test(EditProject::class, ['record' => $record->slug])
            ->callPageAction(DeleteAction::class);

        self::assertModelMissing($record);
    }

    #[Test, DataProvider(methodName: 'provideValidation')]
    public function canValidateCreate(array $input, array $errors): void
    {
        $data = Project::factory()->make([
            'miniature' => $this->uploadedFile,
            'cover' => $this->uploadedFile,
        ]);

        $input = self::executeCallables($input);

        Livewire::test(CreateProject::class)
            ->fillForm(array_merge($data->toArray(), $input))
            ->call('create')
            ->assertHasFormErrors($errors);
    }

    #[Test, DataProvider(methodName: 'provideValidation')]
    public function canValidateEdit(array $input, array $errors): void
    {
        $data = Project::factory()
            ->make([
                'miniature' => $this->uploadedFile,
                'cover' => $this->uploadedFile,
            ]);

        $input = self::executeCallables($input);
        $record = Project::factory()->create();

        Livewire::test(EditProject::class, ['record' => $record->slug])
            ->fillForm(array_merge($data->toArray(), $input))
            ->call('save')
            ->assertHasFormErrors($errors);
    }
}

<?php

namespace Tests\Feature;

use App\Filament\Resources\ProjectResource;
use App\Models\Genre;
use App\Models\Link;
use App\Models\Project;
use Filament\Pages\Actions\DeleteAction;
use Illuminate\Http\Testing\File;
use Illuminate\Support\Str;
use Livewire\Livewire;
use Livewire\TemporaryUploadedFile;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

class ProjectTest extends TestCase
{
    private File $uploadedFile;

    protected function setUp(): void
    {
        parent::setUp();

        $this->uploadedFile = TemporaryUploadedFile::fake()->image('example.png');
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
            'max length title' => [
                'input' => [
                    'title' => Str::random(31),
                ],
                'errors' => [
                    'title' => 'max',
                ],
            ],
            'max length alternative title' => [
                'input' => [
                    'alternative_title' => Str::random(31),
                ],
                'errors' => [
                    'alternative_title' => 'max',
                ],
            ],
            'max length episodes' => [
                'input' => [
                    'episodes' => Str::random(11),
                ],
                'errors' => [
                    'episodes' => 'max',
                ],
            ],
            'max length year' => [
                'input' => [
                    'year' => 20233,
                ],
                'errors' => [
                    'year' => 'max',
                ],
            ],
            'unique title' => [
                'input' => [
                    'title' => fn () => Project::factory()->createOne()->title,
                ],
                'errors' => [
                    'title' => 'unique',
                ],
            ],
            'max 3 genres' => [
                'input' => [
                    'genres' => fn () => Genre::factory(4)->create()->pluck('id')->toArray(),
                ],
                'errors' => [
                    'genres' => 'max',
                ],
            ],
        ];
    }

    /**
     * @test
     */
    public function canRenderList(): void
    {
        $this->get(ProjectResource::getUrl())->assertSuccessful();
    }

    /**
     * @test
     */
    public function canRenderColumns(): void
    {
        $data = Project::factory(10)->create();

        Livewire::test(ProjectResource\Pages\ListProjects::class)
            ->assertCanSeeTableRecords($data)
            ->assertCanRenderTableColumn('title')
            ->assertCanRenderTableColumn('links_count')
            ->assertCanRenderTableColumn('category')
            ->assertCanRenderTableColumn('created_at')
            ->assertCanRenderTableColumn('updated_at');
    }

    /**
     * @test
     */
    public function canRenderCreate()
    {
        $this->get(ProjectResource::getUrl('create'))->assertSuccessful();
    }

    /**
     * @test
     */
    public function create(): void
    {
        $data = Project::factory()->makeOne();
        $genres = Genre::factory(3)->create()->pluck('id')->toArray();

        Livewire::test(ProjectResource\Pages\CreateProject::class)
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

    #[DataProvider(methodName: 'provideValidation')]
    /**
     * @test
     */
    public function createValidation(array $input, array $errors): void
    {
        $data = Project::factory()
            ->makeOne([
                'miniature' => $this->uploadedFile,
                'cover' => $this->uploadedFile,
            ]);

        if (is_callable($input['title'] ?? null)) {
            $input['title'] = $input['title']();
        }

        if (is_callable($input['genres'] ?? null)) {
            $input['genres'] = $input['genres']();
        }

        Livewire::test(ProjectResource\Pages\CreateProject::class)
            ->fillForm(array_merge($data->toArray(), $input))
            ->call('create')
            ->assertHasFormErrors($errors);
    }

    /**
     * @test
     */
    public function canRenderEdit()
    {
        $data = Project::factory()->createOne();

        $this->get(ProjectResource::getUrl('edit', ['record' => $data]))
            ->assertSuccessful();
    }

    /**
     * @test
     */
    public function edit()
    {
        $record = Project::factory()->createOne();
        $data = Project::factory()->makeOne();
        $genres = Genre::factory(3)->create()->pluck('id')->toArray();

        Livewire::test(ProjectResource\Pages\EditProject::class, ['record' => $record->slug])
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

    #[DataProvider(methodName: 'provideValidation')]
    /**
     * @test
     */
    public function editValidation(array $input, array $errors)
    {
        $data = Project::factory()
            ->makeOne([
                'miniature' => $this->uploadedFile,
                'cover' => $this->uploadedFile,
            ]);

        if (is_callable($input['title'] ?? null)) {
            $input['title'] = $input['title']();
        }

        if (is_callable($input['genres'] ?? null)) {
            $input['genres'] = $input['genres']();
        }

        $record = Project::factory()->createOne();

        Livewire::test(ProjectResource\Pages\EditProject::class, ['record' => $record->slug])
            ->fillForm(array_merge($data->toArray(), $input))
            ->call('save')
            ->assertHasFormErrors($errors);
    }

    /**
     * @test
     */
    public function delete()
    {
        $record = Project::factory()->createOne();

        Livewire::test(ProjectResource\Pages\EditProject::class, ['record' => $record->slug])
            ->callPageAction(DeleteAction::class);

        self::assertModelMissing($record);
    }

    /**
     * @test
     */
    public function cannotDeleteIfHasLinks()
    {
        $record = Project::factory()->createOne();
        $link = Link::factory()->createOne(['project_id' => $record->id]);

        Livewire::test(ProjectResource\Pages\EditProject::class, ['record' => $record->slug])
            ->callPageAction(DeleteAction::class);

        self::assertModelExists($record);
        self::assertModelExists($link);
    }
}

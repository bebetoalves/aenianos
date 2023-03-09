<?php

namespace Tests\Feature;

use App\Filament\Resources\LinkResource;
use App\Models\Link;
use Filament\Pages\Actions\CreateAction;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\EditAction;
use Illuminate\Support\Str;
use Livewire\Livewire;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

class LinkTest extends TestCase
{
    public static function provideValidation(): array
    {
        return [
            'required fields' => [
                'input' => [
                    'name' => null,
                    'url' => null,
                    'project_id' => null,
                    'quality' => null,
                    'server_id' => null,
                ],
                'errors' => [
                    'name' => 'required',
                    'url' => 'required',
                    'project_id' => 'required',
                    'quality' => 'required',
                    'server_id' => 'required',
                ],
            ],
            'max length name' => [
                'input' => [
                    'name' => Str::random(31),
                ],
                'errors' => [
                    'name' => 'max',
                ],
            ],
            'valid url' => [
                'input' => [
                    'url' => 'google',
                ],
                'errors' => [
                    'url' => 'url',
                ],
            ],
        ];
    }

    /**
     * @test
     */
    public function canRenderPage(): void
    {
        $this->get(LinkResource::getUrl())->assertSuccessful();
    }

    /**
     * @test
     */
    public function canRenderColumns(): void
    {
        $data = Link::factory(10)->create();

        Livewire::test(LinkResource\Pages\ManageLinks::class)
            ->assertCanSeeTableRecords($data)
            ->assertCanRenderTableColumn('name')
            ->assertCanRenderTableColumn('project.title')
            ->assertCanRenderTableColumn('quality')
            ->assertCanRenderTableColumn('created_at')
            ->assertCanRenderTableColumn('updated_at');
    }

    /**
     * @test
     */
    public function create(): void
    {
        $data = Link::factory()->makeOne();

        Livewire::test(LinkResource\Pages\ManageLinks::class)
            ->callPageAction(CreateAction::class, [
                'name' => $data->name,
                'url' => $data->url,
                'project_id' => $data->project_id,
                'quality' => $data->quality,
                'server_id' => $data->server_id,
            ]);

        self::assertDatabaseHas(Link::class, [
            'name' => $data->name,
            'url' => $data->url,
            'project_id' => $data->project_id,
            'quality' => $data->quality,
            'server_id' => $data->server_id,
        ]);
    }

    #[DataProvider(methodName: 'provideValidation')]
    /**
     * @test
     */
    public function createValidation(array $input, array $errors): void
    {
        $data = Link::factory()->makeOne();

        Livewire::test(LinkResource\Pages\ManageLinks::class)
            ->callPageAction(CreateAction::class, array_merge($data->toArray(), $input))
            ->assertHasPageActionErrors($errors);
    }

    /**
     * @test
     */
    public function edit()
    {
        $record = Link::factory()->createOne();
        $data = Link::factory()->makeOne();

        Livewire::test(LinkResource\Pages\ManageLinks::class)
            ->callTableAction(EditAction::class, $record, [
                'name' => $data->name,
                'url' => $data->url,
                'project_id' => $data->project_id,
                'quality' => $data->quality,
                'server_id' => $data->server_id,
            ]);

        $record->refresh();
        self::assertEquals($data->name, $record->name);
        self::assertEquals($data->url, $record->url);
        self::assertEquals($data->project_id, $record->project_id);
        self::assertEquals($data->quality, $record->quality);
        self::assertEquals($data->server_id, $record->server_id);
    }

    #[DataProvider(methodName: 'provideValidation')]
    /**
     * @test
     */
    public function editValidation(array $input, array $errors)
    {
        $data = Link::factory()->makeOne();
        $record = Link::factory()->createOne();

        Livewire::test(LinkResource\Pages\ManageLinks::class)
            ->callTableAction(EditAction::class, $record, array_merge($data->toArray(), $input))
            ->assertHasTableActionErrors($errors);
    }

    /**
     * @test
     */
    public function delete()
    {
        $record = Link::factory()->createOne();

        Livewire::test(LinkResource\Pages\ManageLinks::class)
            ->callTableAction(DeleteAction::class, $record)
            ->assertHasNoTableActionErrors();

        self::assertModelMissing($record);
    }
}

<?php

namespace Tests\Feature;

use App\Filament\Resources\LinkResource;
use App\Filament\Resources\LinkResource\Pages\ManageLinks;
use App\Models\Link;
use Filament\Pages\Actions\CreateAction;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\EditAction;
use Illuminate\Support\Str;
use Livewire\Livewire;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
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
            'max length' => [
                'input' => [
                    'name' => Str::random(31),
                ],
                'errors' => [
                    'name' => 'max',
                ],
            ],
            'valid url' => [
                'input' => [
                    'url' => Str::random(),
                ],
                'errors' => [
                    'url' => 'url',
                ],
            ],
        ];
    }

    #[Test]
    public function canRenderList(): void
    {
        self::get(LinkResource::getUrl())->assertSuccessful();
    }

    #[Test]
    public function canRenderColumns(): void
    {
        $data = Link::factory(10)->create();

        Livewire::test(ManageLinks::class)
            ->assertCanSeeTableRecords($data)
            ->assertCanRenderTableColumn('name')
            ->assertCanRenderTableColumn('project.title')
            ->assertCanRenderTableColumn('quality')
            ->assertCanRenderTableColumn('created_at')
            ->assertCanRenderTableColumn('updated_at');
    }

    #[Test]
    public function canCreate(): void
    {
        $data = Link::factory()->make();

        Livewire::test(ManageLinks::class)
            ->callPageAction(CreateAction::class, [
                'name' => $data->name,
                'url' => $data->url,
                'project_id' => $data->project_id,
                'quality' => $data->quality,
                'server_id' => $data->server_id,
            ])
            ->assertHasNoPageActionErrors();

        self::assertDatabaseHas(Link::class, [
            'name' => $data->name,
            'url' => $data->url,
            'project_id' => $data->project_id,
            'quality' => $data->quality,
            'server_id' => $data->server_id,
        ]);
    }

    #[Test]
    public function canEdit(): void
    {
        $record = Link::factory()->create();
        $data = Link::factory()->make();

        Livewire::test(ManageLinks::class)
            ->callTableAction(EditAction::class, $record, [
                'name' => $data->name,
                'url' => $data->url,
                'project_id' => $data->project_id,
                'quality' => $data->quality,
                'server_id' => $data->server_id,
            ])
            ->assertHasNoTableActionErrors();

        $record->refresh();

        self::assertEquals($data->name, $record->name);
        self::assertEquals($data->url, $record->url);
        self::assertEquals($data->project_id, $record->project_id);
        self::assertEquals($data->quality, $record->quality);
        self::assertEquals($data->server_id, $record->server_id);
    }

    #[Test]
    public function canDelete(): void
    {
        $record = Link::factory()->create();

        Livewire::test(ManageLinks::class)
            ->callTableAction(DeleteAction::class, $record)
            ->assertHasNoTableActionErrors();

        self::assertModelMissing($record);
    }

    #[Test, DataProvider(methodName: 'provideValidation')]
    public function canValidateCreate(array $input, array $errors): void
    {
        $data = Link::factory()->make();

        Livewire::test(ManageLinks::class)
            ->callPageAction(CreateAction::class, array_merge($data->toArray(), $input))
            ->assertHasPageActionErrors($errors);
    }

    #[Test, DataProvider(methodName: 'provideValidation')]
    public function canValidateEdit(array $input, array $errors): void
    {
        $data = Link::factory()->make();
        $record = Link::factory()->create();

        Livewire::test(ManageLinks::class)
            ->callTableAction(EditAction::class, $record, array_merge($data->toArray(), $input))
            ->assertHasTableActionErrors($errors);
    }
}

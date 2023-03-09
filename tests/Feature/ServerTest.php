<?php

namespace Tests\Feature;

use App\Filament\Resources\ServerResource;
use App\Models\Link;
use App\Models\Server;
use Filament\Pages\Actions\CreateAction;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\EditAction;
use Illuminate\Support\Str;
use Livewire\Livewire;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class ServerTest extends TestCase
{
    public static function provideValidation(): array
    {
        return [
            'required fields' => [
                'input' => [
                    'name' => null,
                    'icon' => null,
                ],
                'errors' => [
                    'name' => 'required',
                    'icon' => 'required',
                ],
            ],
            'max length name' => [
                'input' => [
                    'name' => Str::random(31),
                    'icon' => [placekitten(1280, 720)],
                ],
                'errors' => [
                    'name' => 'max',
                ],
            ],
            'unique name' => [
                'input' => [
                    'name' => fn () => Server::factory()->createOne()->name,
                    'icon' => [placekitten(32, 32)],
                ],
                'errors' => [
                    'name' => 'unique',
                ],
            ],
        ];
    }

    #[Test]
    public function canRenderPage(): void
    {
        $this->get(ServerResource::getUrl())->assertSuccessful();
    }

    #[Test]
    public function canRenderColumns(): void
    {
        $data = Server::factory(10)->create();

        Livewire::test(ServerResource\Pages\ManageServers::class)
            ->assertCanSeeTableRecords($data)
            ->assertCanRenderTableColumn('name')
            ->assertCanRenderTableColumn('links_count')
            ->assertCanRenderTableColumn('icon')
            ->assertCanRenderTableColumn('created_at')
            ->assertCanRenderTableColumn('updated_at');
    }

    #[Test]
    public function canCreate(): void
    {
        $data = Server::factory()->makeOne();

        Livewire::test(ServerResource\Pages\ManageServers::class)
            ->callPageAction(CreateAction::class, [
                'name' => $data->name,
                'icon' => [$data->icon],
            ]);

        self::assertDatabaseHas(Server::class, [
            'name' => $data->name,
            'icon' => $data->icon,
        ]);
    }

    #[Test]
    public function canEdit()
    {
        $record = Server::factory()->createOne();
        $data = Server::factory()->makeOne();

        Livewire::test(ServerResource\Pages\ManageServers::class)
            ->callTableAction(EditAction::class, $record, [
                'name' => $data->name,
                'icon' => [$data->icon],
            ]);

        $record->refresh();
        self::assertEquals($data->name, $record->name);
        self::assertEquals($data->icon, $record->icon);
    }

    #[Test]
    public function canDelete()
    {
        $record = Server::factory()->createOne();

        Livewire::test(ServerResource\Pages\ManageServers::class)
            ->callTableAction(DeleteAction::class, $record)
            ->assertHasNoTableActionErrors();

        self::assertModelMissing($record);
    }

    #[Test]
    public function cannotDeleteIfHasLinks()
    {
        $record = Server::factory()->createOne();
        $link = Link::factory()->createOne(['server_id' => $record->id]);

        Livewire::test(ServerResource\Pages\ManageServers::class)
            ->callTableAction(DeleteAction::class, $record)
            ->assertNotified();

        self::assertModelExists($record);
        self::assertModelExists($link);
    }

    #[Test]
    #[DataProvider(methodName: 'provideValidation')]
    public function createValidation(array $input, array $errors): void
    {
        if (is_callable($input['name'])) {
            $input['name'] = $input['name']();
        }

        $data = Server::factory()->makeOne();

        Livewire::test(ServerResource\Pages\ManageServers::class)
            ->callPageAction(CreateAction::class, array_merge($data->toArray(), $input))
            ->assertHasPageActionErrors($errors);
    }

    #[Test]
    #[DataProvider(methodName: 'provideValidation')]
    public function editValidation(array $input, array $errors)
    {
        if (is_callable($input['name'])) {
            $input['name'] = $input['name']();
        }

        $data = Server::factory()->makeOne();
        $record = Server::factory()->createOne();

        Livewire::test(ServerResource\Pages\ManageServers::class)
            ->callTableAction(EditAction::class, $record, array_merge($data->toArray(), $input))
            ->assertHasTableActionErrors($errors);
    }
}

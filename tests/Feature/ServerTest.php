<?php

namespace Tests\Feature;

use App\Filament\Resources\ServerResource;
use App\Filament\Resources\ServerResource\Pages\ManageServers;
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
            'max length' => [
                'input' => [
                    'name' => Str::random(31),
                ],
                'errors' => [
                    'name' => 'max',
                ],
            ],
            'unique fields' => [
                'input' => [
                    'name' => fn () => Server::factory()->create()->name,
                ],
                'errors' => [
                    'name' => 'unique',
                ],
            ],
        ];
    }

    #[Test]
    public function canRenderList(): void
    {
        self::get(ServerResource::getUrl())->assertSuccessful();
    }

    #[Test]
    public function canRenderColumns(): void
    {
        $data = Server::factory(10)->create();

        Livewire::test(ManageServers::class)
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
        $data = Server::factory()->make();

        Livewire::test(ManageServers::class)
            ->callPageAction(CreateAction::class, [
                'name' => $data->name,
                'icon' => [$data->icon],
            ])
            ->assertHasNoPageActionErrors();

        self::assertDatabaseHas(Server::class, [
            'name' => $data->name,
            'icon' => $data->icon,
        ]);
    }

    #[Test]
    public function canEdit(): void
    {
        $record = Server::factory()->create();
        $data = Server::factory()->make();

        Livewire::test(ManageServers::class)
            ->callTableAction(EditAction::class, $record, [
                'name' => $data->name,
                'icon' => [$data->icon],
            ])
            ->assertHasNoTableActionErrors();

        $record->refresh();
        self::assertEquals($data->name, $record->name);
        self::assertEquals($data->icon, $record->icon);
    }

    #[Test]
    public function canDelete(): void
    {
        $record = Server::factory()->create();

        Livewire::test(ManageServers::class)
            ->callTableAction(DeleteAction::class, $record)
            ->assertHasNoTableActionErrors();

        self::assertModelMissing($record);
    }

    #[Test]
    public function cannotDeleteIfHasLinks(): void
    {
        $record = Server::factory()->create();
        $link = Link::factory()->create(['server_id' => $record->getKey()]);

        Livewire::test(ManageServers::class)
            ->callTableAction(DeleteAction::class, $record)
            ->assertNotified();

        self::assertModelExists($record);
        self::assertModelExists($link);
    }

    #[Test, DataProvider(methodName: 'provideValidation')]
    public function canValidateCreate(array $input, array $errors): void
    {
        $data = Server::factory()->make(['icon' => [placekitten(32, 32)]]);
        $input = self::executeCallables($input);

        Livewire::test(ManageServers::class)
            ->callPageAction(CreateAction::class, array_merge($data->toArray(), $input))
            ->assertHasPageActionErrors($errors);
    }

    #[Test, DataProvider(methodName: 'provideValidation')]
    public function canValidateEdit(array $input, array $errors): void
    {
        $data = Server::factory()->make(['icon' => [placekitten(32, 32)]]);
        $record = Server::factory()->create();

        $input = self::executeCallables($input);

        Livewire::test(ManageServers::class)
            ->callTableAction(EditAction::class, $record, array_merge($data->toArray(), $input))
            ->assertHasTableActionErrors($errors);
    }
}

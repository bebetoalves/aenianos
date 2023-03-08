<?php

namespace Tests\Feature;

use App\Enums\Role;
use App\Filament\Resources\UserResource;
use App\Models\Post;
use App\Models\User;
use Filament\Pages\Actions\CreateAction;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\EditAction;
use Hash;
use Illuminate\Support\Str;
use Livewire\Livewire;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

class UserTest extends TestCase
{
    public function testCanRenderPage(): void
    {
        $this->get(UserResource::getUrl())->assertSuccessful();
    }

    public function testCanRenderColumns(): void
    {
        $data = User::all();

        Livewire::test(UserResource\Pages\ManageUsers::class)
            ->assertCanSeeTableRecords($data)
            ->assertCanRenderTableColumn('name')
            ->assertCanRenderTableColumn('role')
            ->assertCanRenderTableColumn('posts_count')
            ->assertCanRenderTableColumn('created_at')
            ->assertCanRenderTableColumn('updated_at');
    }

    public function testCreate(): void
    {
        $data = User::factory()->makeOne();
        $password = Str::random(8);

        Livewire::test(UserResource\Pages\ManageUsers::class)
            ->callPageAction(CreateAction::class, [
                'name' => $data->name,
                'email' => $data->email,
                'password' => $password,
                'role' => $data->role,
            ]);

        self::assertDatabaseHas(User::class, [
            'name' => $data->name,
            'email' => $data->email,
            'role' => $data->role,
        ]);
    }

    #[DataProvider(methodName: 'provideValidation')]
    public function testCreateValidation(array $input, array $errors): void
    {
        if (is_callable($input['email'] ?? null)) {
            $input['email'] = $input['email']();
        }

        $data = User::factory()->makeOne();

        Livewire::test(UserResource\Pages\ManageUsers::class)
            ->callPageAction(CreateAction::class, array_merge($data->toArray(), $input))
            ->assertHasPageActionErrors($errors);
    }

    public function testEdit()
    {
        $record = User::factory()->createOne();
        $data = User::factory()->makeOne();
        $password = Str::random(8);

        Livewire::test(UserResource\Pages\ManageUsers::class)
            ->callTableAction(EditAction::class, $record, [
                'name' => $data->name,
                'email' => $data->email,
                'password' => $password,
                'role' => $data->role,
            ]);

        $record->refresh();
        self::assertEquals($data->name, $record->name);
        self::assertEquals($data->email, $record->email);
        self::assertEquals($data->role, $record->role);
        self::assertTrue(Hash::check($password, $record->password));
    }

    public function testEditWithoutPassword()
    {
        $record = User::factory()->createOne();
        $data = User::factory()->makeOne();

        Livewire::test(UserResource\Pages\ManageUsers::class)
            ->callTableAction(EditAction::class, $record, [
                'name' => $data->name,
                'email' => $data->email,
                'role' => $data->role,
            ]);

        $record->refresh();
        self::assertEquals($data->name, $record->name);
        self::assertEquals($data->email, $record->email);
        self::assertEquals($data->role, $record->role);
        self::assertNotEquals($data->password, $record->password);
    }

    #[DataProvider(methodName: 'provideValidation')]
    public function testEditValidation(array $input, array $errors)
    {
        if (is_callable($input['email'] ?? null)) {
            $input['email'] = $input['email']();
        }

        if ($errors['password'] ?? null === 'required') {
            unset($errors['password']);
        }

        $data = User::factory()->makeOne();
        $record = User::factory()->createOne();

        Livewire::test(UserResource\Pages\ManageUsers::class)
            ->callTableAction(EditAction::class, $record, array_merge($data->toArray(), $input))
            ->assertHasTableActionErrors($errors);
    }

    public function testDelete()
    {
        $record = User::factory()->createOne();

        Livewire::test(UserResource\Pages\ManageUsers::class)
            ->callTableAction(DeleteAction::class, $record)
            ->assertHasNoTableActionErrors();

        self::assertModelMissing($record);
    }

    public function testCannotDeleteIfHasPosts()
    {
        $record = User::factory()->createOne();
        $post = Post::factory()->createOne(['user_id' => $record->id]);

        Livewire::test(UserResource\Pages\ManageUsers::class)
            ->callTableAction(DeleteAction::class, $record)
            ->assertNotified();

        self::assertModelExists($record);
        self::assertModelExists($post);
    }

    public function testCannotDeleteItSelf()
    {
        $record = User::factory()->createOne(['role' => Role::ADMIN]);
        $this->actingAs($record);

        Livewire::test(UserResource\Pages\ManageUsers::class)
            ->callTableAction(DeleteAction::class, $record)
            ->assertNotified();

        self::assertModelExists($record);
    }

    public static function provideValidation(): array
    {
        return [
            'required fields' => [
                'input' => [
                    'name' => null,
                    'email' => null,
                    'password' => null,
                    'role' => null,
                ],
                'errors' => [
                    'name' => 'required',
                    'email' => 'required',
                    'password' => 'required',
                    'role' => 'required',
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
            'valid email' => [
                'input' => [
                    'email' => Str::random(),
                ],
                'errors' => [
                    'email' => 'email',
                ],
            ],
            'min length password' => [
                'input' => [
                    'password' => Str::length(7),
                ],
                'errors' => [
                    'password' => 'min',
                ],
            ],
            'unique email' => [
                'input' => [
                    'email' => fn () => User::factory()->createOne()->email,
                ],
                'errors' => [
                    'email' => 'unique',
                ],
            ],
        ];
    }
}

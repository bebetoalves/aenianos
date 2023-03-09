<?php

namespace Tests\Feature;

use App\Enums\Role;
use App\Filament\Resources\UserResource;
use App\Models\Post;
use App\Models\User;
use Filament\Pages\Actions\CreateAction;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\EditAction;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Livewire\Livewire;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class UserTest extends TestCase
{
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

    #[Test]
    public function canRenderPage(): void
    {
        $this->get(UserResource::getUrl())->assertSuccessful();
    }

    #[Test]
    public function canRenderColumns(): void
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

    #[Test]
    public function canCreate(): void
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

    #[Test]
    public function canEdit()
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

    #[Test]
    public function editWithoutPassword()
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

    #[Test]
    public function canDelete()
    {
        $record = User::factory()->createOne();

        Livewire::test(UserResource\Pages\ManageUsers::class)
            ->callTableAction(DeleteAction::class, $record)
            ->assertHasNoTableActionErrors();

        self::assertModelMissing($record);
    }

    #[Test]
    public function cannotDeleteIfHasPosts()
    {
        $record = User::factory()->createOne();
        $post = Post::factory()->createOne(['user_id' => $record->id]);

        Livewire::test(UserResource\Pages\ManageUsers::class)
            ->callTableAction(DeleteAction::class, $record)
            ->assertNotified();

        self::assertModelExists($record);
        self::assertModelExists($post);
    }

    #[Test]
    public function cannotDeleteItSelf()
    {
        $record = User::factory()->createOne(['role' => Role::ADMIN]);
        $this->actingAs($record);

        Livewire::test(UserResource\Pages\ManageUsers::class)
            ->callTableAction(DeleteAction::class, $record)
            ->assertNotified();

        self::assertModelExists($record);
    }

    #[Test]
    #[DataProvider(methodName: 'provideValidation')]
    public function createValidation(array $input, array $errors): void
    {
        if (is_callable($input['email'] ?? null)) {
            $input['email'] = $input['email']();
        }

        $data = User::factory()->makeOne();

        Livewire::test(UserResource\Pages\ManageUsers::class)
            ->callPageAction(CreateAction::class, array_merge($data->toArray(), $input))
            ->assertHasPageActionErrors($errors);
    }

    #[Test]
    #[DataProvider(methodName: 'provideValidation')]
    public function editValidation(array $input, array $errors)
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
}

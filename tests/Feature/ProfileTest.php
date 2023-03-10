<?php

namespace Tests\Feature;

use App\Filament\Pages\Profile;
use App\Models\User;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Livewire;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class ProfileTest extends TestCase
{
    use WithFaker;

    public static function provideValidation(): array
    {
        return [
            'required fields' => [
                'input' => [
                    'name' => null,
                    'email' => null,
                ],
                'errors' => [
                    'name' => 'required',
                    'email' => 'required',
                ],
            ],
            'invalid email' => [
                'input' => [
                    'email' => Str::random(),
                ],
                'errors' => [
                    'email' => 'email',
                ],
            ],
            'unique email' => [
                'input' => [
                    'email' => fn () => User::factory()->create()->email,
                ],
                'errors' => [
                    'email' => 'unique',
                ],
            ],
            'invalid current password' => [
                'input' => [
                    'current_password' => Str::random(),
                ],
                'errors' => [
                    'current_password' => 'current_password',
                ],
            ],
            'min length fields' => [
                'input' => [
                    'new_password' => 'pass',
                    'new_password_confirmation' => 'pass',
                ],
                'errors' => [
                    'new_password' => 'min',
                    'new_password_confirmation' => 'min',
                ],
            ],
            'current password is required if new password is present' => [
                'input' => [
                    'new_password' => 'password',
                    'new_password_confirmation' => 'password',
                ],
                'errors' => [
                    'current_password' => 'required_with',
                ],
            ],
            'new password should match password confirmation' => [
                'input' => [
                    'new_password' => Str::random(),
                    'new_password_confirmation' => Str::random(),
                ],
                'errors' => [
                    'new_password' => 'confirmed',
                ],
            ],
        ];
    }

    #[Test]
    public function canRenderPage()
    {
        $this->get(Profile::getUrl())->assertSuccessful();
    }

    #[Test]
    public function canChangeGeneral()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $name = $this->faker->name();
        $email = $this->faker->valid()->email();

        Livewire::test(Profile::class)
            ->fillForm([
                'name' => $name,
                'email' => $email,
            ])
            ->call('submit')
            ->assertHasNoPageActionErrors();

        $user->refresh();

        self::assertEquals($user->name, $name);
        self::assertEquals($user->email, $email);
    }

    #[Test]
    public function canChangePassword()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        Livewire::test(Profile::class)
            ->fillForm([
                'current_password' => 'password',
                'new_password' => 'new_password',
                'new_password_confirmation' => 'new_password',
            ])
            ->call('submit')
            ->assertHasNoPageActionErrors();

        $user->refresh();

        self::assertTrue(Hash::check('new_password', $user->password));
    }

    #[Test, DataProvider(methodName: 'provideValidation')]
    public function canValidate(array $input, array $errors)
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $input = $this->executeCallables($input);

        Livewire::test(Profile::class)
            ->fillForm($input)
            ->call('submit')
            ->assertHasErrors($errors);
    }
}

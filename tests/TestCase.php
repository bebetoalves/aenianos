<?php

namespace Tests;

use App\Enums\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication, LazilyRefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $user = User::factory()->create([
            'role' => Role::ADMIN,
        ]);

        $this->actingAs($user);
    }

    public function executeCallables(array $data): array
    {
        foreach ($data as $key => $value) {
            if (is_callable($value)) {
                $data[$key] = $value();
            }
        }

        return $data;
    }
}

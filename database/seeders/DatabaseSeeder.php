<?php

namespace Database\Seeders;

use App\Enums\Role;
use App\Models\Genre;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::factory()->create([
            'email' => 'admin@example.com',
            'role' => Role::ADMIN,
        ]);

        Genre::factory()->createMany(array_map(fn (string $name) => ['name' => $name], $this->genres()));
    }

    private function genres(): array
    {
        return [
            'Ação',
            'Artes Marciais',
            'Aventura',
            'Carros',
            'Comédia',
            'Crianças',
            'Demência',
            'Demonios',
            'Drama',
            'Ecchi',
            'Escola',
            'Espaço',
            'Esportes',
            'Fantasia',
            'Ficção Científica',
            'Harém',
            'Histórico',
            'Horror',
            'Jogos',
            'Josei',
            'Magia',
            'Mecha',
            'Militar',
            'Mistério',
            'Musical',
            'Paródia',
            'Policial',
            'Psicológico',
            'Romance',
            'Samurai',
            'Seinen',
            'Shoujo',
            'Shoujo Ai',
            'Shounen',
            'Shounen Ai',
            'Slice of Life',
            'Sobrenatural',
            'Super Poderes',
            'Suspense',
            'Vampiro',
        ];
    }
}

<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash; // <- Importante para criptografar a senha

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Cria o Administrador
        User::factory()->create([
            'name' => 'Admin',
            'email' => 'admin@admin.com',
            'password' => Hash::make('12345678'), // Senha padrão para testes
            'role' => 'admin',
        ]);

        // 2. Cria o Professor
        User::factory()->create([
            'name' => 'Professor João',
            'email' => 'professor@admin.com',
            'password' => Hash::make('12345678'),
            'role' => 'professor',
        ]);

        // 3. Cria a Portaria
        User::factory()->create([
            'name' => 'Portaria Principal',
            'email' => 'portaria@admin.com',
            'password' => Hash::make('12345678'),
            'role' => 'portaria',
        ]);
    }
}
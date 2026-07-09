<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);

        User::factory()->create([
            'name' => 'Administrador',
            'email' => 'admin@sectormueble.com',
            'password' => bcrypt('admin123'),
            'is_admin' => true,
        ]);

        \App\Models\Cupon::create([
            'codigo' => 'MUEBLE10',
            'tipo' => 'porcentaje',
            'valor' => 10.00,
            'activo' => true,
        ]);

        \App\Models\Cupon::create([
            'codigo' => 'BIENVENIDA500',
            'tipo' => 'fijo',
            'valor' => 500.00,
            'activo' => true,
        ]);

        $this->call(ProductoSeeder::class);
    }
}

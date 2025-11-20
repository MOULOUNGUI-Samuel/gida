<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Demandes;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Appel des seeders dans l'ordre correct
        $this->call([
            EntrepriseSeeder::class,  // D'abord les entreprises
            AdminUserSeeder::class,
            AddUsersSeeder::class,
            UserSeeder::class,        // Puis les utilisateurs de test
        ]);
    }
}

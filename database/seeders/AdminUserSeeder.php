<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create admin user
        User::create([
            'nom' => 'Administrateur',
            'username' => 'admin',
            'code_entreprise' => 'ADMIN001',
            'password' => Hash::make('password'),
            'type' => 0,
            'email' => 'admin@example.com',
            'societe' => 'Admin',
            'fonction' => 'Administrateur',
            'matricule' => 'ADMIN001',
        ]);

        $this->command->info('Admin user created successfully!');
        $this->command->info('Username: admin');
        $this->command->info('Password: password');
    }
}

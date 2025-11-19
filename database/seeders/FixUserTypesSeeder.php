<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;

class FixUserTypesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Corriger le type de l'utilisateur employer
        $employer = User::where('username', 'employer')->first();
        if ($employer) {
            $employer->update(['type' => 1]); // 1 = Employé
        }

        // S'assurer que l'admin a le bon type
        $admin = User::where('username', 'admin')->first();
        if ($admin) {
            $admin->update(['type' => 0]); // 0 = Administrateur
        }
    }
}

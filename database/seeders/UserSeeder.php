<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Entreprise;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Recuperer les entreprises existantes
        $fci = Entreprise::where('code', 'FCI')->first();
        $yod = Entreprise::where('code', 'YOD')->first();
        $comket = Entreprise::where('code', 'COMKET')->first();
        $alphon = Entreprise::where('code', 'ALPHON')->first();

        $users = [
            // Administrateur systeme
            [
                'nom' => 'Admin Systeme',
                'username' => 'admin',
                'matricule' => 'ADM001',
                'email' => 'admin@gida.com',
                'password' => Hash::make('password123'),
                'type' => 0, // Administrateur
                'code_entreprise' => 'SYSTEM',
                'societe' => 'GIDA',
                'fonction' => 'Administrateur Systeme',
                'entreprise_id' => null,
            ],

            // Utilisateurs FCI
            [
                'nom' => 'Jean Dupont',
                'username' => 'jean.dupont',
                'matricule' => 'FCI-EMP001',
                'email' => 'jean.dupont@fci-group.fr',
                'password' => Hash::make('password123'),
                'type' => 1, // Employe
                'code_entreprise' => $fci?->code ?? 'FCI',
                'societe' => $fci?->societe ?? 'FCI Group',
                'fonction' => 'Gestionnaire Financier',
                'entreprise_id' => $fci?->id,
            ],
            [
                'nom' => 'Marie Martin',
                'username' => 'marie.martin',
                'matricule' => 'FCI-EMP002',
                'email' => 'marie.martin@fci-group.fr',
                'password' => Hash::make('password123'),
                'type' => 1, // Employe
                'code_entreprise' => $fci?->code ?? 'FCI',
                'societe' => $fci?->societe ?? 'FCI Group',
                'fonction' => 'Analyste Financier',
                'entreprise_id' => $fci?->id,
            ],
            [
                'nom' => 'Support FCI',
                'username' => 'support.fci',
                'matricule' => 'FCI-SUP001',
                'email' => 'support@fci-group.fr',
                'password' => Hash::make('password123'),
                'type' => 2, // Entreprise Support
                'code_entreprise' => $fci?->code ?? 'FCI',
                'societe' => $fci?->societe ?? 'FCI Group',
                'fonction' => 'Support Client',
                'entreprise_id' => $fci?->id,
            ],

            // Utilisateurs YOD
            [
                'nom' => 'Pierre Bernard',
                'username' => 'pierre.bernard',
                'matricule' => 'YOD-EMP001',
                'email' => 'pierre.bernard@yod-engineering.fr',
                'password' => Hash::make('password123'),
                'type' => 1, // Employe
                'code_entreprise' => $yod?->code ?? 'YOD',
                'societe' => $yod?->societe ?? 'YOD Engineering',
                'fonction' => 'Chef de Projet',
                'entreprise_id' => $yod?->id,
            ],
            [
                'nom' => 'Sophie Leroy',
                'username' => 'sophie.leroy',
                'matricule' => 'YOD-EMP002',
                'email' => 'sophie.leroy@yod-engineering.fr',
                'password' => Hash::make('password123'),
                'type' => 1, // Employe
                'code_entreprise' => $yod?->code ?? 'YOD',
                'societe' => $yod?->societe ?? 'YOD Engineering',
                'fonction' => 'Ingenieur Etudes',
                'entreprise_id' => $yod?->id,
            ],

            // Utilisateurs COMKET
            [
                'nom' => 'Luc Dubois',
                'username' => 'luc.dubois',
                'matricule' => 'COM-EMP001',
                'email' => 'luc.dubois@comketing.fr',
                'password' => Hash::make('password123'),
                'type' => 1, // Employe
                'code_entreprise' => $comket?->code ?? 'COMKET',
                'societe' => $comket?->societe ?? 'Comketing Agency',
                'fonction' => 'Directeur Marketing',
                'entreprise_id' => $comket?->id,
            ],
            [
                'nom' => 'Emma Rousseau',
                'username' => 'emma.rousseau',
                'matricule' => 'COM-EMP002',
                'email' => 'emma.rousseau@comketing.fr',
                'password' => Hash::make('password123'),
                'type' => 1, // Employe
                'code_entreprise' => $comket?->code ?? 'COMKET',
                'societe' => $comket?->societe ?? 'Comketing Agency',
                'fonction' => 'Chargee de Communication',
                'entreprise_id' => $comket?->id,
            ],

            // Utilisateurs ALPHON
            [
                'nom' => 'Thomas Petit',
                'username' => 'thomas.petit',
                'matricule' => 'ALP-EMP001',
                'email' => 'thomas.petit@alphon-consulting.fr',
                'password' => Hash::make('password123'),
                'type' => 1, // Employe
                'code_entreprise' => $alphon?->code ?? 'ALPHON',
                'societe' => $alphon?->societe ?? 'Alphon Consulting Group',
                'fonction' => 'Consultant Senior',
                'entreprise_id' => $alphon?->id,
            ],
            [
                'nom' => 'Laura Moreau',
                'username' => 'laura.moreau',
                'matricule' => 'ALP-EMP002',
                'email' => 'laura.moreau@alphon-consulting.fr',
                'password' => Hash::make('password123'),
                'type' => 1, // Employe
                'code_entreprise' => $alphon?->code ?? 'ALPHON',
                'societe' => $alphon?->societe ?? 'Alphon Consulting Group',
                'fonction' => 'Consultante',
                'entreprise_id' => $alphon?->id,
            ],

            // Utilisateurs sans entreprise
            [
                'nom' => 'Test User',
                'username' => 'test.user',
                'matricule' => 'TEST001',
                'email' => 'test@example.com',
                'password' => Hash::make('password123'),
                'type' => 1, // Employe
                'code_entreprise' => 'TEST',
                'societe' => null,
                'fonction' => 'Testeur',
                'entreprise_id' => null,
            ],
        ];

        foreach ($users as $userData) {
            User::updateOrCreate(
                ['username' => $userData['username']],
                $userData
            );
        }
    }
}

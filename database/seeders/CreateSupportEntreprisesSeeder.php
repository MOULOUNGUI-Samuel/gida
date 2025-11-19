<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\Entreprise;
use App\Models\User;

class CreateSupportEntreprisesSeeder extends Seeder
{
    public function run(): void
    {
        $supportCompanies = [
            [
                'entreprise' => [
                    'matricule' => 'TECH001',
                    'code' => 'TECH-SUP',
                    'nom' => 'Tech Support Solutions'
                ],
                'user' => [
                    'username' => 'tech.support',
                    'name' => 'Tech Support Admin',
                    'type' => 2, // support
                    'password' => 'tech123!',
                    'email' => 'tech.support@example.com',
                    'fonction' => 'Support Technique',
                ]
            ],
            [
                'entreprise' => [
                    'matricule' => 'FIN001',
                    'code' => 'FIN-SUP',
                    'nom' => 'Finance Support Services'
                ],
                'user' => [
                    'username' => 'fin.support',
                    'name' => 'Finance Support Admin',
                    'type' => 2,
                    'password' => 'fin123!',
                    'email' => 'fin.support@example.com',
                    'fonction' => 'Support Financier',
                ]
            ],
            [
                'entreprise' => [
                    'matricule' => 'RH001',
                    'code' => 'RH-SUP',
                    'nom' => 'RH Support & Conseil'
                ],
                'user' => [
                    'username' => 'rh.support',
                    'name' => 'RH Support Admin',
                    'type' => 2,
                    'password' => 'rh123!',
                    'email' => 'rh.support@example.com',
                    'fonction' => 'Support RH',
                ]
            ]
        ];

        foreach ($supportCompanies as $company) {
            // Créer ou mettre à jour l'entreprise
            $entreprise = Entreprise::updateOrCreate(
                ['code' => $company['entreprise']['code']],
                $company['entreprise']
            );

            // Créer l'utilisateur support lié à cette entreprise
            $userData = array_merge($company['user'], [
                'code_entreprise' => $entreprise->code,
                'societe' => $entreprise->nom,
                'matricule' => $entreprise->matricule . '-U001',
                'password' => Hash::make($company['user']['password']),
                'email_verified_at' => now(),
                'entreprise_id' => $entreprise->id
            ]);

            User::updateOrCreate(
                ['username' => $userData['username']],
                $userData
            );
        }

        $this->command->info('Support companies and their users created successfully.');
        $this->command->table(
            ['Company', 'Code', 'Support Username', 'Initial Password'],
            collect($supportCompanies)->map(function ($company) {
                return [
                    $company['entreprise']['nom'],
                    $company['entreprise']['code'],
                    $company['user']['username'],
                    $company['user']['password']
                ];
            })->toArray()
        );
    }
}
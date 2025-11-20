<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class EntrepriseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $entreprises = [
            [
                'matricule' => 'FCI001',
                'code' => 'FCI',
                'nom' => 'FCI (Finance/Investissement)',
                'societe' => 'FCI Group',
                'adresse' => '123 Avenue des Finances, Paris 75001',
                'telephone' => '+33 1 23 45 67 89',
                'email' => 'contact@fci-group.fr',
                'description' => 'Spécialisée dans les services financiers et l\'investissement',
                'active' => true
            ],
            [
                'matricule' => 'YOD001',
                'code' => 'YOD',
                'nom' => 'YOD INGÉNIERIE (Technique/Ingénierie)',
                'societe' => 'YOD Engineering',
                'adresse' => '456 Rue de l\'Innovation, Lyon 69001',
                'telephone' => '+33 4 12 34 56 78',
                'email' => 'contact@yod-engineering.fr',
                'description' => 'Bureau d\'études techniques et d\'ingénierie spécialisé',
                'active' => true
            ],
            [
                'matricule' => 'COM001',
                'code' => 'COMKET',
                'nom' => 'COMKETING (Marketing/Communication)',
                'societe' => 'Comketing Agency',
                'adresse' => '789 Boulevard du Marketing, Marseille 13001',
                'telephone' => '+33 4 98 76 54 32',
                'email' => 'info@comketing.fr',
                'description' => 'Agence de marketing digital et communication',
                'active' => true
            ],
            [
                'matricule' => 'ALP001',
                'code' => 'ALPHON',
                'nom' => 'ALPHON CONSULTING (Management/Organisation)',
                'societe' => 'Alphon Consulting Group',
                'adresse' => '321 Avenue du Management, Toulouse 31000',
                'telephone' => '+33 5 61 23 45 67',
                'email' => 'contact@alphon-consulting.fr',
                'description' => 'Cabinet de conseil en management et organisation',
                'active' => true
            ],
            [
                'matricule' => 'BEF001',
                'code' => 'BEFEV',
                'nom' => 'BEFEV (Bureau d\'Études et de Formation en Évaluation)',
                'societe' => 'BEFEV Consulting',
                'adresse' => '654 Rue de la Formation, Bordeaux 33000',
                'telephone' => '+33 5 56 78 90 12',
                'email' => 'contact@befev.fr',
                'description' => 'Bureau d\'études spécialisé en formation et évaluation',
                'active' => true
            ],
            [
                'matricule' => 'OCT001',
                'code' => 'OCT',
                'nom' => 'OCT (Organisation et Conseil en Technologie)',
                'societe' => 'OCT Solutions',
                'adresse' => '987 Place de la Technologie, Nantes 44000',
                'telephone' => '+33 2 40 12 34 56',
                'email' => 'contact@oct-solutions.fr',
                'description' => 'Conseil en organisation et solutions technologiques',
                'active' => true
            ]
        ];

        foreach ($entreprises as $entreprise) {
            \App\Models\Entreprise::updateOrCreate(
                ['code' => $entreprise['code']],
                $entreprise
            );
        }
    }
}

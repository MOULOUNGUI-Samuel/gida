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
                'nom' => 'FCI (Finance/Investissement)'
            ],
            [
                'matricule' => 'YOD001',
                'code' => 'YOD',
                'nom' => 'YOD INGÉNIERIE (Technique/Ingénierie)'
            ],
            [
                'matricule' => 'COM001',
                'code' => 'COMKET',
                'nom' => 'COMKETING (Marketing/Communication)'
            ],
            [
                'matricule' => 'ALP001',
                'code' => 'ALPHON',
                'nom' => 'ALPHON CONSULTING (Management/Organisation)'
            ],
            [
                'matricule' => 'BEF001',
                'code' => 'BEFEV',
                'nom' => 'BEFEV (Bureau d\'Études et de Formation en Évaluation)'
            ],
            [
                'matricule' => 'OCT001',
                'code' => 'OCT',
                'nom' => 'OCT (Organisation et Conseil en Technologie)'
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

@extends('layouts.appAdministration')

@section('title', 'Qualification & Affectation - GIDA')

@section('content')
    <!-- Header -->
    <div class="admin-header">
        <h1>Qualification & Affectation</h1>
        <div>
            <button class="admin-btn" onclick="window.location.href='{{ route('dashboard') }}'">← Retour au dashboard</button>
        </div>
    </div>

    <!-- Statistiques -->
    <div class="stats-grid">
        <div class="stat-card">
            <h3>Demandes en attente</h3>
            <div class="number">{{ $demandes->count() }}</div>
            <div class="trend">À traiter</div>
        </div>
        <div class="stat-card">
            <h3>Priorité critique</h3>
            <div class="number">{{ $demandes->where('priorite', 'Critique')->count() }}</div>
            <div class="trend" style="color: #dc3545;">Urgent</div>
        </div>
        <div class="stat-card">
            <h3>Priorité urgente</h3>
            <div class="number">{{ $demandes->where('priorite', 'Urgente')->count() }}</div>
            <div class="trend" style="color: #ffc107;">Important</div>
        </div>
        <div class="stat-card">
            <h3>Priorité normale</h3>
            <div class="number">{{ $demandes->where('priorite', 'Normale')->count() }}</div>
            <div class="trend">Standard</div>
        </div>
    </div>

    <!-- Tableau des demandes à qualifier -->
    <table class="admin-table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Titre</th>
                <th>Demandeur</th>
                <th>Société</th>
                <th>Catégorie</th>
                <th>Priorité</th>
                <th>Date création</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($demandes as $demande)
                <tr data-id="{{ $demande->id }}">
                    <td>{{ $demande->id }}</td>
                    <td>{{ $demande->titre }}</td>
                    <td>{{ $demande->nom }}</td>
                    <td>{{ $demande->societe }}</td>
                    <td>{{ $demande->categorie }}</td>
                    <td>
                        <span class="priority-{{ strtolower($demande->priorite) }}">
                            {{ $demande->priorite }}
                        </span>
                    </td>
                    <td>{{ $demande->formatted_created_at }}</td>
                    <td>
                        <button class="admin-btn" onclick="viewDemande({{ $demande->id }})">Voir</button>
                        <button class="admin-btn secondary" onclick="qualifyDemande({{ $demande->id }})">Qualifier</button>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="8" style="text-align: center; padding: 2rem; color: #666;">
                        Aucune demande en attente de qualification
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <style>
        .priority-normale {
            color: #28a745;
            font-weight: bold;
        }
        
        .priority-urgente {
            color: #ffc107;
            font-weight: bold;
        }
        
        .priority-critique {
            color: #dc3545;
            font-weight: bold;
        }
    </style>

    <script>
        function viewDemande(id) {
            window.location.href = `/demandes/${id}`;
        }

        function qualifyDemande(id) {
            if (confirm('Voulez-vous qualifier cette demande ?')) {
                // Ici vous pouvez ajouter la logique de qualification
                window.location.href = `/demandes/${id}/edit`;
            }
        }
    </script>
@endsection

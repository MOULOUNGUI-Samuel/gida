@extends('layouts.appAdministration')

@section('content')
    <div class="admin-header">
        <h1>Accueil Support</h1>
    </div>

    <section class="stats-grid">
        <div class="stat-card">
            <h3>Total demandes</h3>
            <div class="number">{{ $stats['total'] ?? 0 }}</div>
        </div>
        <div class="stat-card">
            <h3>En attente</h3>
            <div class="number">{{ $stats['en_attente'] ?? 0 }}</div>
        </div>
        <div class="stat-card">
            <h3>En traitement</h3>
            <div class="number">{{ $stats['en_traitement'] ?? 0 }}</div>
        </div>
        <div class="stat-card">
            <h3>Résolues/Validées/Clôturées</h3>
            <div class="number">{{ $stats['resolues'] ?? 0 }}</div>
        </div>
        <div class="stat-card">
            <h3>À risque</h3>
            <div class="number">{{ $stats['a_risque'] ?? 0 }}</div>
        </div>
    </section>

    <section>
        @if(isset($recent) && $recent->count() > 0)
            <h2 style="margin: 12px 0 16px;">Demandes récentes</h2>
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Titre</th>
                        <th>Origine</th>
                        <th>Priorité</th>
                        <th>Workflow</th>
                        <th>Créée</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                @foreach($recent as $demande)
                    <tr>
                        <td>{{ $demande->id }}</td>
                        <td>{{ $demande->titre }}</td>
                        <td>{{ $demande->nom ?? ($demande->user->nom ?? '—') }}</td>
                        <td>{{ $demande->priorite }}</td>
                        <td>{{ $demande->workflow_label ?? $demande->workflow_status }}</td>
                        <td>{{ $demande->created_at->format('d/m/Y H:i') }}</td>
                        <td>
                            <a class="admin-btn" href="{{ route('demandes.show', $demande->id) }}">Voir</a>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        @else
            <div class="no-demandes">
                <p>Aucune demande récente.</p>
            </div>
        @endif
    </section>
@endsection

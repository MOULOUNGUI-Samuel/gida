@extends('layouts.appAdministration')

@section('content')
    <div class="admin-header">
        <h1>Demandes en attente</h1>
    </div>

    <section>
        @if(isset($pending) && $pending->count() > 0)
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
                @foreach($pending as $demande)
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
                <p>Aucune demande en attente pour votre société pour le moment.</p>
            </div>
        @endif
    </section>
@endsection

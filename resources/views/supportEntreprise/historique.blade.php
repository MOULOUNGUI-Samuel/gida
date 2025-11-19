@extends('layouts.appAdministration')

@section('content')
    <div class="admin-header">
        <h1>Historique des demandes traitées</h1>
    </div>

    <section>
        @if(isset($treated) && $treated->count() > 0)
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Titre</th>
                        <th>Origine</th>
                        <th>Priorité</th>
                        <th>Workflow</th>
                        <th>Date clôture</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                @foreach($treated as $demande)
                    <tr>
                        <td>{{ $demande->id }}</td>
                        <td>{{ $demande->titre }}</td>
                        <td>{{ $demande->nom ?? ($demande->user->nom ?? '—') }}</td>
                        <td>{{ $demande->priorite }}</td>
                        <td>{{ $demande->workflow_label ?? $demande->workflow_status }}</td>
                        <td>{{ optional($demande->date_fermeture)->format('d/m/Y H:i') ?? '—' }}</td>
                        <td>
                            <a class="admin-btn" href="{{ route('demandes.show', $demande->id) }}">Voir</a>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        @else
            <div class="no-demandes">
                <p>Aucune demande traitée par votre société pour le moment.</p>
            </div>
        @endif
    </section>
@endsection

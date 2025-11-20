@extends('layouts.appAdministration')

@section('content')
<div class="container-fluid p-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="mb-0">Gestion des Entreprises</h2>
        <a href="{{ route('entreprises.create') }}" class="admin-btn">
            <i class="fas fa-plus"></i> Nouvelle Entreprise
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="card shadow-sm">
        <div class="card-body">
            <div class="table-responsive">
                <div class="table-responsive">
                    <table class="admin-table" id="users-table">
                    <thead class="table-light">
                        <tr>
                            <th>Code</th>
                            <th>Nom</th>
                            <th>Matricule</th>
                            <th>Société</th>
                            <th>Email</th>
                            <th>Téléphone</th>
                            <th>Utilisateurs</th>
                            <th>Statut</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($entreprises as $entreprise)
                            <tr>
                                <td><strong>{{ $entreprise->code }}</strong></td>
                                <td>{{ $entreprise->nom }}</td>
                                <td>{{ $entreprise->matricule ?? '-' }}</td>
                                <td>{{ $entreprise->societe ?? '-' }}</td>
                                <td>{{ $entreprise->email ?? '-' }}</td>
                                <td>{{ $entreprise->telephone ?? '-' }}</td>
                                <td>
                                    <span class="badge bg-info">
                                        {{ $entreprise->users_count }} utilisateur(s)
                                    </span>
                                </td>
                                <td>
                                    @if($entreprise->active)
                                        <span class="badge bg-success">Active</span>
                                    @else
                                        <span class="badge bg-secondary">Inactive</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('entreprises.show', $entreprise->id) }}"
                                           class="btn btn-sm btn-info" title="Voir">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('entreprises.edit', $entreprise->id) }}"
                                           class="btn btn-sm btn-warning" title="Modifier">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <a href="{{ route('entreprises.add-users', $entreprise->id) }}"
                                           class="btn btn-sm btn-primary" title="Gérer les utilisateurs">
                                            <i class="fas fa-users"></i>
                                        </a>
                                        <form action="{{ route('entreprises.toggle-active', $entreprise->id) }}"
                                              method="POST" style="display: inline;">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="btn btn-sm btn-secondary"
                                                    title="{{ $entreprise->active ? 'Désactiver' : 'Activer' }}">
                                                <i class="fas fa-power-off"></i>
                                            </button>
                                        </form>
                                        <form action="{{ route('entreprises.destroy', $entreprise->id) }}"
                                              method="POST"
                                              onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cette entreprise ?');"
                                              style="display: inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger" title="Supprimer">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="text-center py-4">
                                    <p class="text-muted mb-0">Aucune entreprise enregistrée.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<style>
    .container-fluid {
        background: #f5f7fa;
        min-height: 100vh;
    }

    .card {
        border: none;
        border-radius: 10px;
    }

    .table th {
        font-weight: 600;
        color: #495057;
        border-bottom: 2px solid #dee2e6;
    }

    .btn-group .btn {
        margin: 0 2px;
    }

    .badge {
        padding: 6px 12px;
        font-weight: 500;
    }
</style>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
@endsection

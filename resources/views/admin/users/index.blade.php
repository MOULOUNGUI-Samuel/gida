@extends('layouts.appAdministration')

@section('title', 'Gestion des Utilisateurs - GIDA')

@section('content')
<div class="container-fluid p-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="mb-0">Gestion des utilisateurs</h2>
        <a href="{{ route('users.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Nouvel utilisateur
        </a>
    </div>

    @include('partials.flash-messages')

    <div class="card shadow-sm">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover" id="users-table">
                    <thead class="table-light">
                        <tr>
                            <th>ID</th>
                            <th>Nom</th>
                            <th>Rôle</th>
                            <th>Code Société</th>
                            <th>Entreprise</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($users as $user)
                            <tr>
                                <td>{{ $user->id }}</td>
                                <td>{{ $user->nom }}</td>
                                <td>
                                    @php
                                        $roleMap = [
                                            0 => ['label' => 'Administrateur', 'class' => 'danger'],
                                            1 => ['label' => 'Employe', 'class' => 'primary'],
                                            2 => ['label' => 'Entreprise Support', 'class' => 'success']
                                        ];
                                        $roleInfo = $roleMap[$user->type] ?? ['label' => 'Employe', 'class' => 'secondary'];
                                    @endphp
                                    <span class="badge bg-{{ $roleInfo['class'] }}">{{ $roleInfo['label'] }}</span>
                                </td>
                                <td>{{ $user->code_entreprise ?? '-' }}</td>
                                <td>{{ $user->entreprise->nom ?? '-' }}</td>
                                <td>
                                    <a href="{{ route('users.edit', $user->id) }}" class="btn btn-sm btn-warning">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('users.destroy', $user->id) }}" method="POST" class="d-inline"
                                          onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cet utilisateur ?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center text-muted">Aucun utilisateur trouvé</td>
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
    }
</style>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
@endsection

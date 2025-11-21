@extends('layouts.appAdministration')

@section('title', 'Modifier un Utilisateur - GIDA')

@section('content')
    <div class="container-fluid p-4">
        <div class="mb-4">
            <h2 class="mb-0">Modifier l'utilisateur</h2>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('users') }}">Utilisateurs</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Modifier</li>
                </ol>
            </nav>
        </div>

        @include('partials.flash-messages')

        <div class="card shadow-sm">
            <div class="card-body">
                <form action="{{ route('users.update', $user->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="name" class="form-label">Nom complet <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" id="name"
                                name="name" value="{{ old('name', $user->nom) }}" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="role" class="form-label">Rôle <span class="text-danger">*</span></label>
                            @php
                                $roleMap = [
                                    0 => 'Administrateur',
                                    1 => 'Employe',
                                    2 => 'Entreprise Support',
                                ];
                                $currentRole = $roleMap[$user->type] ?? 'Employe';
                            @endphp
                            <select class="form-select @error('role') is-invalid @enderror" id="role" name="role"
                                required>
                                <option value="">Sélectionner un rôle</option>
                                <option value="Administrateur"
                                    {{ old('role', $currentRole) == 'Administrateur' ? 'selected' : '' }}>Administrateur
                                </option>
                                <option value="Employe" {{ old('role', $currentRole) == 'Employe' ? 'selected' : '' }}>
                                    Employe</option>
                                <option value="Entreprise Support"
                                    {{ old('role', $currentRole) == 'Entreprise Support' ? 'selected' : '' }}>Entreprise
                                    Support</option>
                            </select>
                            @error('role')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="company" class="form-label">Code Société <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('company') is-invalid @enderror" id="company"
                                name="company" value="{{ old('company', $user->code_entreprise) }}"
                                placeholder="Ex: COMKETING" required>
                            @error('company')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="entreprise_id" class="form-label">Entreprise</label>
                            <select class="form-select @error('entreprise_id') is-invalid @enderror" id="entreprise_id"
                                name="entreprise_id">
                                <option value="">Sélectionner une entreprise</option>
                                @foreach ($entreprises as $entreprise)
                                    <option value="{{ $entreprise->id }}"
                                        {{ old('entreprise_id', $user->entreprise_id) == $entreprise->id ? 'selected' : '' }}>
                                        {{ $entreprise->nom }}
                                    </option>
                                @endforeach
                            </select>
                            @error('entreprise_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                            <input type="email" class="form-control @error('email') is-invalid @enderror" id="email"
                                name="email" value="{{ old('email', $user->email) }}" required>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="password" class="form-label">Nouveau mot de passe</label>
                            <input type="password" class="form-control @error('password') is-invalid @enderror" id="password"
                                name="password" minlength="6">
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="form-text text-muted">Laissez vide pour conserver le mot de passe actuel. Minimum 6
                                caractères si modifié.</small>
                        </div>
                    </div>
                    <div>
                        <div class="d-flex justify-content-end gap-2">
                            <a href="{{ route('users') }}" class="btn btn-secondary">
                                <i class="fas fa-times"></i> Annuler
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Mettre à jour
                            </button>
                        </div>
                </form>
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

        .form-label {
            font-weight: 600;
            color: #495057;
        }

        .breadcrumb {
            background: transparent;
            padding: 0;
            margin-top: 10px;
        }
    </style>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
@endsection

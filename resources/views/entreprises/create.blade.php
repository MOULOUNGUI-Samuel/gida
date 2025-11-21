@extends('layouts.appAdministration')

@section('content')
    <div class="container-fluid p-4">
        <div class="mb-4">
            <h2 class="mb-0">Créer une Nouvelle Entreprise</h2>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('entreprises.index') }}">Entreprises</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Nouvelle</li>
                </ol>
            </nav>
        </div>

        @include('partials.flash-messages')

        <div class="card shadow-sm">
            <div class="card-body">
                <form action="{{ route('entreprises.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="code" class="form-label">Code <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('code') is-invalid @enderror" id="code"
                                name="code" value="{{ old('code') }}" required>
                            @error('code')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="nom" class="form-label">Nom <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('nom') is-invalid @enderror" id="nom"
                                name="nom" value="{{ old('nom') }}" required>
                            @error('nom')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="matricule" class="form-label">Matricule</label>
                            <input type="text" class="form-control @error('matricule') is-invalid @enderror"
                                id="matricule" name="matricule" value="{{ old('matricule') }}">
                            @error('matricule')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="societe" class="form-label">Raison sociale</label>
                            <input type="text" class="form-control @error('societe') is-invalid @enderror" id="societe"
                                name="societe" value="{{ old('societe') }}">
                            @error('societe')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control @error('email') is-invalid @enderror" id="email"
                                name="email" value="{{ old('email') }}">
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="telephone" class="form-label">Téléphone</label>
                            <input type="text" class="form-control @error('telephone') is-invalid @enderror"
                                id="telephone" name="telephone" value="{{ old('telephone') }}">
                            @error('telephone')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="adresse" class="form-label">Adresse</label>
                        <input type="text" class="form-control @error('adresse') is-invalid @enderror" id="adresse"
                            name="adresse" value="{{ old('adresse') }}">
                        @error('adresse')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="description" class="form-label">Description</label>
                        <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description"
                            rows="4">{{ old('description') }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="logo" class="form-label">Logo de l'entreprise</label>
                        <input type="file" class="form-control @error('logo') is-invalid @enderror" id="logo"
                            name="logo" accept="image/*">
                        @error('logo')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="form-text text-muted">Formats acceptés: JPG, PNG, GIF (Max: 2MB)</small>
                    </div>

                    <div class="mb-3 form-check">
                        <input type="checkbox" class="form-check-input" id="active" name="active"
                            {{ old('active', true) ? 'checked' : '' }} value="1">
                        <label class="form-check-label" for="active">
                            Entreprise active
                        </label>
                    </div>

                    <div class="d-flex justify-content-end gap-2">
                        <a href="{{ route('entreprises.index') }}" class="btn btn-secondary">
                            <i class="fas fa-times"></i> Annuler
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Enregistrer
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

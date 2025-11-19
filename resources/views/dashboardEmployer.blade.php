@extends('layouts.appEmployer')

@section('title', 'Tableau de bord GIDA')

@section('content')
    <!-- HEADER UTILISATEUR + CTA -->
    <div class="gida-header" id="gida-header">
      <h1>Tableau de bord GIDA</h1>
      <button class="gida-btn" id="gida-new-btn" onclick="window.location.href='{{ route('nouvelledemande') }}'">➕ Nouvelle demande</button>
    </div>

    <!-- DASHBOARD SYNTHÉTIQUE -->
    <section class="gida-dashboard" id="section-dashboard" aria-label="Synthèse tickets">
      <h2>Statut de mes demandes</h2>
      
      @if($demandes->count() > 0)
        <table class="gida-table" aria-label="Tableau de tickets">
          <thead>
            <tr>
              <th>Référence</th>
              <th>Date</th>
              <th>Catégorie</th>
              <th>Priorité</th>
              <th>Statut</th>
              <th>Action</th>
            </tr>
          </thead>
          <tbody>
            @foreach($demandes as $demande)
              <tr>
                <td>{{ $demande->reference }}</td>
                <td>{{ $demande->formatted_created_at }}</td>
                <td>{{ $demande->categorie }}</td>
                <td>{{ $demande->priorite }}</td>
                <td class="status-{{ $demande->statut }}">
                  {{ ucfirst($demande->statut) }}
                </td>
                <td>
                  <button class="gida-btn" onclick="window.location.href='{{ route('demandes.show', $demande->id) }}'">Voir</button>
                </td>
              </tr>
            @endforeach
          </tbody>
        </table>
      @else
        <div class="no-demandes">
          <p>Aucune demande trouvée. Créez votre première demande !</p>
          <button class="gida-btn" onclick="window.location.href='{{ route('nouvelledemande') }}'">Créer une demande</button>
        </div>
      @endif
    </section>
@endsection
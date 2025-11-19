@extends('layouts.appEmployer')

@section('title', 'Historique - GIDA')

@section('content')
    <!-- HEADER UTILISATEUR + CTA -->
    <div class="gida-header" id="gida-header">
      <h1>Historique de mes demandes</h1>
      <button class="gida-btn" onclick="window.location.href='{{ route('dashboardEmployer') }}'">← Retour au tableau de bord</button>
    </div>

    <!-- HISTORIQUE DES DEMANDES -->
    <section class="gida-histo" id="section-histo" aria-label="historique">
      <div class="historique-container">
        @if($demandes->count() > 0)
          <table class="gida-table">
            <thead>
              <tr>
                <th>Réf.</th>
                <th>Date</th>
                <th>Catégorie</th>
                <th>Statut</th>
                <th>Action</th>
              </tr>
            </thead>
            <tbody>
                  @foreach($demandes as $demande)
          @if($demande->statut === 'clôturé')
              <tr>
                <td>{{ $demande->reference }}</td>
                <td>{{ $demande->formatted_created_at }}</td>
                <td>{{ $demande->categorie }}</td>
                <td class="status-{{ $demande->statut }}">
                  {{ ucfirst($demande->statut) }}
                </td>
                <td>
                  <button class="gida-btn" onclick="window.location.href='{{ route('demandes.show', $demande->id) }}'">Voir</button>
                </td>
              </tr>
          @endif
      @endforeach
            </tbody>
          </table>
        @else
          <div class="no-demandes">
            <p>Aucune demande trouvée dans l'historique.</p>
            <button class="gida-btn" onclick="window.location.href='{{ route('nouvelledemande') }}'">Créer une demande</button>
          </div>
        @endif
      </div>
    </section>

    <style>
      .historique-container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 2rem;
        background: white;
        border-radius: 8px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
      }

      .gida-table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 1rem;
      }

      .gida-table th,
      .gida-table td {
        padding: 1rem;
        text-align: left;
        border-bottom: 1px solid #eee;
      }

      .gida-table th {
        background: #f8f9fa;
        font-weight: 600;
        color: #333;
      }

      .status-en attente { color: #ff9800; font-weight: bold; }
      .status-en cours { color: #2196f3; font-weight: bold; }
      .status-à risque { color: #f44336; font-weight: bold; }
      .status-clôturé { color: #4caf50; font-weight: bold; }

      .no-demandes {
        text-align: center;
        padding: 3rem;
        color: #666;
      }

      .no-demandes p {
        margin-bottom: 1rem;
        font-size: 1.1rem;
      }
    </style>
@endsection
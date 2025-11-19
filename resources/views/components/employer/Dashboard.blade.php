
@extends('layouts.app')

@section('title', 'Dashboard - GIDA')

@section('content')
<div class="fade-in"></div>

<!-- MAIN CONTENT -->
  <main class="gida-main" aria-label="Contenu principal">
    <!-- HEADER UTILISATEUR + CTA -->
    <div class="gida-header" id="gida-header">
      <h1>Tableau de bord GIDA</h1>
      <button class="gida-btn" id="gida-new-btn">➕ Nouvelle demande</button>
    </div>

    <!-- DASHBOARD SYNTHÉTIQUE -->
    <section class="gida-dashboard" id="section-dashboard" aria-label="Synthèse tickets">
      <h2>Statut de mes demandes</h2>
      <table class="gida-table" aria-label="Tableau de tickets">
        <thead>
          <tr><th>Référence</th><th>Date</th><th>Catégorie</th><th>Priorité</th><th>Statut</th><th>Action</th></tr>
        </thead>
        <tbody>
          <tr><td>TK-00125</td><td>01/08/2025</td><td>Informatique</td><td>Urgent</td><td style="color:#ee9115;font-weight:bold;">En cours</td><td><button class="gida-btn">Voir</button></td></tr>
          <tr><td>TK-00123</td><td>30/07/2025</td><td>RH</td><td>Normale</td><td style="color:#32a852;font-weight:bold;">Clos</td><td><button class="gida-btn">Voir</button></td></tr>
        </tbody>
      </table>
      <!-- Zone de filtre dynamique (placeholer) -->
    </section>

    @endsection

   
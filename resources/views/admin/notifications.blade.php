@extends('layouts.appAdministration')

@section('title', 'Reporting & KPIs - GIDA')

@section('content')
    <div class="admin-header">
        <h1>Notifications</h1>
        <div>
            <button class="admin-btn" onclick="window.location.href='{{ route('dashboard') }}'">← Retour au dashboard</button>
        </div>
    </div>
    <!-- NOTIFICATIONS -->
    <section class="gida-notifs" id="section-notif" aria-label="Notifications utilisateur">
      <div class="notifications-container">
        <div class="notifications-header">
          <h2>Vos notifications</h2>
          <div class="view-toggle">
            <button class="view-toggle-btn active" data-view="list">
              <i class="fas fa-list"></i> Vue liste
            </button>
            <button class="view-toggle-btn" data-view="table">
              <i class="fas fa-table"></i> Vue tableau
            </button>
          </div>
        </div>
        
        @if(isset($notifications) && count($notifications) > 0)
          <!-- Vue Liste -->
          <div class="notifications-view list-view active">
            @foreach($notifications as $notification)
              @if (is_null($notification->read) || $notification->read == false)
                <div class="notification-item info" onclick="window.location.href='{{ route('notifications.notificationAdmin_show', $notification->notification_id) }}'">
                  <div class="notification-icon">🔵</div>
                  <div class="notification-content">
                    <h3>{{ $notification->type_notification }} </h3>
                    <p>Demande  #{{ $notification->id_demande }} :  {{ $notification->message }}</p>
                    <span class="notification-time">Il y a {{ \Carbon\Carbon::parse($notification->created_at)->locale('fr')->diffForHumans(now(), true) }}</span>
                  </div>
                </div>
    
              @else
                <div class="notification-item warning" onclick="window.location.href='{{ route('notifications.notificationAdmin_show', $notification->notification_id) }}'">
                  <div class="notification-icon">🟡</div>
                  <div class="notification-content">
                    <h3>{{ $notification->type_notification }} </h3>
                    <p>Demande  #{{ $notification->id_demande }} :  {{ $notification->message }}</p>
                    <span class="notification-time">Il y a {{ \Carbon\Carbon::parse($notification->created_at)->locale('fr')->diffForHumans(now(), true) }}</span>
                  </div>
                </div>

               @endif

            @endforeach

            <!-- Vue Tableau -->
            <div class="notifications-view table-view">
              <table class="notifications-table">
                <thead>
                  <tr>
                    <th>Statut</th>
                    <th>Type</th>
                    <th>Demande</th>
                    <th>Message</th>
                    <th>Date</th>
                    <th>Actions</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach($notifications as $notification)
                    <tr class="{{ is_null($notification->read) || $notification->read == false ? 'unread' : 'read' }}">
                      <td>
                        <div class="notification-status">
                          {!! is_null($notification->read) || $notification->read == false ? '🔵' : '🟡' !!}
                        </div>
                      </td>
                      <td>{{ $notification->type_notification }}</td>
                      <td>#{{ $notification->id_demande }}</td>
                      <td>{{ $notification->message }}</td>
                      <td>{{ \Carbon\Carbon::parse($notification->created_at)->locale('fr')->diffForHumans(now(), true) }}</td>
                      <td>
                        <a href="{{ route('notifications.notificationAdmin_show', $notification->notification_id) }}" 
                           class="btn btn-sm btn-primary">Voir</a>
                      </td>
                    </tr>
                  @endforeach
                </tbody>
              </table>
            </div>
          </div>
          @else 
            <p><span class="fs-6 text-black fw-bold">Aucune notification actuellement </span></p>
          @endif
          
        <div class="notifications-actions">
          <button class="gida-btn gida-btn-secondary" id="markAllAsRead">Marquer tout comme lu</button>
          <button class="gida-btn gida-btn-primary" id="refreshNotifications">Actualiser</button>
        </div>
      </div>
    </section>


    <style>
      .notifications-container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 2rem;
        background: white;
        border-radius: 8px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
      }

      .notifications-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 2rem;
        padding-bottom: 1rem;
        border-bottom: 2px solid #eee;
      }

      .view-toggle {
        display: flex;
        gap: 0.5rem;
      }

      .view-toggle-btn {
        padding: 0.5rem 1rem;
        border: 1px solid #ddd;
        background: white;
        border-radius: 4px;
        cursor: pointer;
        transition: all 0.2s;
      }

      .view-toggle-btn.active {
        background: #007bff;
        color: white;
        border-color: #0056b3;
      }

      /* Styles pour la vue tableau */
      .notifications-view {
        display: none;
      }

      .notifications-view.active {
        display: block;
      }

      .notifications-table {
        width: 100%;
        border-collapse: collapse;
        margin-bottom: 2rem;
      }

      .notifications-table th,
      .notifications-table td {
        padding: 1rem;
        border: 1px solid #dee2e6;
        text-align: left;
      }

      .notifications-table th {
        background: #f8f9fa;
        font-weight: 600;
      }

      .notifications-table tr.unread {
        background: #d1ecf1;
      }

      .notifications-table tr.read {
        background: #fff3cd;
      }

      .notifications-table tr:hover {
        background: #e9ecef;
      }

      .notifications-container h2 {
        color: #333;
        margin-bottom: 2rem;
        padding-bottom: 1rem;
        border-bottom: 2px solid #eee;
      }

      .notifications-list {
        margin-bottom: 2rem;
      }

      .notification-item {
        display: flex;
        align-items: flex-start;
        gap: 1rem;
        padding: 1.5rem;
        margin-bottom: 1rem;
        border-radius: 8px;
        border-left: 4px solid;
        background: #f8f9fa;
        transition: transform 0.2s;
      }

      .notification-item:hover {
        transform: translateX(5px);
      }

      .notification-item.success {
        border-left-color: #28a745;
        background: #d4edda;
      }

      .notification-item.warning {
        border-left-color: #ffc107;
        background: #fff3cd;
      }

      .notification-item.info {
        border-left-color: #17a2b8;
        background: #d1ecf1;
      }

      .notification-item.error {
        border-left-color: #dc3545;
        background: #f8d7da;
      }

      .notification-icon {
        font-size: 1.5rem;
        flex-shrink: 0;
      }

      .notification-content {
        flex: 1;
      }

      .notification-content h3 {
        margin: 0 0 0.5rem 0;
        color: #333;
        font-size: 1.1rem;
      }

      .notification-content p {
        margin: 0 0 0.5rem 0;
        color: #555;
        line-height: 1.4;
      }

      .notification-time {
        font-size: 0.8rem;
        color: #666;
      }

      .notifications-actions {
        display: flex;
        gap: 1rem;
        justify-content: center;
        padding-top: 1rem;
        border-top: 1px solid #eee;
      }

      .gida-btn-secondary {
        background: #6c757d;
        color: white;
      }

      .gida-btn-primary {
        background: #007bff;
        color: white;
      }
    </style>

    
    <script>
      document.addEventListener('DOMContentLoaded', function() {
        // Sélectionner les éléments
        const viewButtons = document.querySelectorAll('.view-toggle-btn');
        const views = document.querySelectorAll('.notifications-view');
        
        // Gérer le basculement des vues
        viewButtons.forEach(button => {
          button.addEventListener('click', () => {
            // Retirer la classe active de tous les boutons
            viewButtons.forEach(btn => btn.classList.remove('active'));
            // Ajouter la classe active au bouton cliqué
            button.classList.add('active');
            
            // Masquer toutes les vues
            views.forEach(view => view.classList.remove('active'));
            // Afficher la vue correspondante
            const viewToShow = document.querySelector(`.${button.dataset.view}-view`);
            if (viewToShow) {
              viewToShow.classList.add('active');
            }
          });
        });

        // Gérer le rafraîchissement
        document.getElementById('refreshNotifications').addEventListener('click', () => {
          location.reload();
        });

        // Gérer "Marquer tout comme lu"
        document.getElementById('markAllAsRead').addEventListener('click', async () => {
          try {
            const response = await fetch('/notifications/mark-all-as-read', {
              method: 'POST',
              headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
              }
            });

            if (response.ok) {
              location.reload();
            }
          } catch (error) {
            console.error('Erreur:', error);
          }
        });
      });
    </script>
@endsection
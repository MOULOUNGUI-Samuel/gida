
@extends('layouts.appEmployer')

@section('title', 'Notifications - GIDA')

@section('content')
    <!-- HEADER UTILISATEUR + CTA -->
    <div class="gida-header" id="gida-header">
      <h1>Notifications</h1>
      <button class="gida-btn" onclick="window.location.href='{{ route('dashboardEmployer') }}'">← Retour au tableau de bord</button>
    </div>

    <!-- NOTIFICATIONS -->
    <section class="gida-notifs" id="section-notif" aria-label="Notifications utilisateur">
      <div class="notifications-container">
        <h2>Vos notifications</h2>
        
          @if(isset($notifications) && count($notifications) > 0)
            @foreach($notifications as $notification)
              @if (is_null($notification->read) || $notification->read == false)
                <div class="notification-item info" onclick="window.location.href='{{ route('notifications.notification_show', $notification->notification_id) }}'">
                  <div class="notification-icon">🔵</div>
                  <div class="notification-content">
                    <h3>{{ $notification->type_notification }} </h3>
                    <p>Demande  #{{ $notification->id_demande }} :  {{ $notification->message }}</p>
                    <span class="notification-time">Il y a {{ \Carbon\Carbon::parse($notification->created_at)->locale('fr')->diffForHumans(now(), true) }}</span>
                  </div>
                </div>
    
              @else
                <div class="notification-item warning" onclick="window.location.href='{{ route('notifications.notification_show', $notification->notification_id) }}'">
                  <div class="notification-icon">🟡</div>
                  <div class="notification-content">
                    <h3>{{ $notification->type_notification }} </h3>
                    <p>Demande  #{{ $notification->id_demande }} :  {{ $notification->message }}</p>
                    <span class="notification-time">Il y a {{ \Carbon\Carbon::parse($notification->created_at)->locale('fr')->diffForHumans(now(), true) }}</span>
                  </div>
                </div> 

              @endif

            @endforeach 
          @else
            <p><span class="fs-6 text-black fw-bold">Aucune notification</span></p>
          @endif
          
          
          
          
         
        </div>
        
        <div class="notifications-actions">
          <button class="gida-btn gida-btn-secondary">Marquer tout comme lu</button>
          <button class="gida-btn gida-btn-primary">Actualiser</button>
        </div>
      </div>
    </section>

    <style>
      .notifications-container {
        max-width: 800px;
        margin: 0 auto;
        padding: 2rem;
        background: white;
        border-radius: 8px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
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
      

function timeAgo($datetime) {
    $now = Carbon::now();
    $date = Carbon::parse($datetime);
    $diffInSeconds = $now->diffInSeconds($date);

    if ($diffInSeconds < 60) {
        return "Il y a {$diffInSeconds} seconde(s)";
    }

    $diffInMinutes = $now->diffInMinutes($date);
    if ($diffInMinutes < 60) {
        return "Il y a {$diffInMinutes} minute(s)";
    }

    $diffInHours = $now->diffInHours($date);
    if ($diffInHours < 24) {
        return "Il y a {$diffInHours} heure(s)";
    }

    $diffInDays = $now->diffInDays($date);
    return "Il y a {$diffInDays} jour(s)";
}

    </script>
@endsection
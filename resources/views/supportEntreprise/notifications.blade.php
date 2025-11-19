@extends('layouts.appAdministration')

@section('content')
    <div class="admin-header">
        <h1>Mes notifications</h1>
    </div>

    <section>
        @if(isset($notifications) && $notifications->count() > 0)
            <div class="gida-dashboard">
                <ul class="notifications-list">
                    @foreach($notifications as $notif)
                        <li style="padding:12px; border-bottom:1px solid #eee;">
                            <strong>{{ $notif->type_notification ?? 'Info' }}:</strong>
                            <div>{{ $notif->message }}</div>
                            <small>{{ $notif->created_at->format('d/m/Y H:i') }}</small>
                        </li>
                    @endforeach
                </ul>
            </div>
        @else
            <div class="no-demandes">
                <p>Vous n'avez aucune notification pour le moment.</p>
            </div>
        @endif
    </section>
@endsection

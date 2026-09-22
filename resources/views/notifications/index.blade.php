@extends('layouts.admin')

@section('title', 'Notifications')
@section('page_title', 'Notifications')

@section('content')

    <div class="adm-page-header">
        <div>
            <h1>Notifications</h1>
            <p>Updates on your shipments</p>
        </div>
        @if(auth()->user()->unreadNotifications->count() > 0)
            <form method="POST" action="{{ route('notifications.read-all') }}">
                @csrf
                <button type="submit" class="adm-btn adm-btn-outline">Mark all as read</button>
            </form>
        @endif
    </div>

    <div class="adm-card" style="padding: 0; overflow: hidden;">
        @if($notifications->isEmpty())
            <div style="padding: 60px 24px; text-align: center;">
                <p style="font-size: 18px; font-weight: 600; margin: 0 0 8px;">No notifications</p>
                <p style="color: #6B7273;">You're all caught up.</p>
            </div>
        @else
            @foreach($notifications as $notification)
                @php
                    $data = $notification->data;
                    $isUnread = is_null($notification->read_at);
                @endphp
                <div style="padding: 20px 24px; border-bottom: 1px solid #F0F1F1; background: {{ $isUnread ? '#FAFBFB' : '#fff' }}; display: flex; gap: 16px; align-items: flex-start;">
                    <div style="width: 10px; height: 10px; border-radius: 50%; background: {{ $isUnread ? '#FF6B00' : '#CED1D1' }}; margin-top: 6px; flex-shrink: 0;"></div>

                    <div style="flex: 1;">
                        <p style="margin: 0 0 4px; font-weight: 600;">
                            <a href="{{ $data['url'] ?? '#' }}" style="color: #073B3A; text-decoration: none;">
                                {{ $data['tracking_number'] ?? '' }} — {{ $data['status_label'] ?? 'Update' }}
                            </a>
                        </p>
                        @if(!empty($data['location']))
                            <p style="margin: 0; color: #6B7273; font-size: 13px;">📍 {{ $data['location'] }}</p>
                        @endif
                        @if(!empty($data['note']))
                            <p style="margin: 4px 0 0; color: #6B7273; font-size: 13px;">{{ $data['note'] }}</p>
                        @endif
                        <p style="margin: 8px 0 0; color: #9CA3A4; font-size: 12px;">
                            {{ $notification->created_at->diffForHumans() }}
                        </p>
                    </div>

                    @if($isUnread)
                        <form method="POST" action="{{ route('notifications.read', $notification->id) }}">
                            @csrf
                            <button type="submit" class="adm-btn adm-btn-outline adm-btn-sm">Mark read</button>
                        </form>
                    @endif
                </div>
            @endforeach

            <div style="padding: 20px 24px; border-top: 1px solid #F0F1F1;">
                {{ $notifications->links() }}
            </div>
        @endif
    </div>

@endsection
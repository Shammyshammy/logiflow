@extends('layouts.admin')

@section('title', 'Activity Log')
@section('page_title', 'Activity Log')

@section('content')

    <div class="adm-page-header">
        <div>
            <h1>Activity Log</h1>
            <p>Complete audit trail of actions in the system</p>
        </div>
    </div>

    <div class="adm-card" style="margin-bottom: 20px;">
        <form method="GET" style="display: flex; gap: 12px; align-items: flex-end; flex-wrap: wrap;">
            <div style="min-width: 240px;">
                <label class="adm-label">Filter by action</label>
                <select name="action" class="adm-select">
                    <option value="">All actions</option>
                    @foreach($actions as $action)
                        <option value="{{ $action }}" @selected(request('action') === $action)>{{ $action }}</option>
                    @endforeach
                </select>
            </div>
            <div style="display: flex; gap: 8px;">
                <button type="submit" class="adm-btn adm-btn-primary">Filter</button>
                @if(request()->has('action'))
                    <a href="{{ route('activity.index') }}" class="adm-btn adm-btn-outline">Clear</a>
                @endif
            </div>
        </form>
    </div>

    <div class="adm-card" style="padding: 0; overflow: hidden;">
        @if($logs->isEmpty())
            <div style="padding: 60px 24px; text-align: center; color: #6B7273;">No activity yet.</div>
        @else
            <div style="overflow-x: auto;">
                <table class="adm-table">
                    <thead>
                        <tr>
                            <th>When</th>
                            <th>User</th>
                            <th>Action</th>
                            <th>Description</th>
                            <th>IP</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($logs as $log)
                            <tr>
                                <td style="font-size: 13px; color: #6B7273;">
                                    {{ $log->created_at->format('M d, Y') }}<br>
                                    <span style="font-size: 11px;">{{ $log->created_at->format('H:i') }}</span>
                                </td>
                                <td>{{ $log->user->name ?? 'System' }}</td>
                                <td>
                                    <code style="font-size: 12px; background: #F5F6F7; padding: 3px 8px; border-radius: 4px;">
                                        {{ $log->action }}
                                    </code>
                                </td>
                                <td style="font-size: 13px;">{{ $log->description }}</td>
                                <td style="font-size: 12px; color: #9CA3A4; font-family: monospace;">{{ $log->ip ?? '—' }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div style="padding: 20px 24px; border-top: 1px solid #F0F1F1;">
                {{ $logs->links() }}
            </div>
        @endif
    </div>

@endsection
@extends('layouts.app')
@section('header_title', 'Activity Log')

@section('styles')
<style>
    .activity-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    .activity-row {
        display: flex;
        align-items: flex-start;
        gap: 12px;
    }
    @media (max-width: 576px) {
        .activity-header {
            flex-direction: column;
            align-items: flex-start;
            gap: 12px;
        }
        .activity-row {
            flex-direction: column;
            align-items: flex-start;
        }
        .activity-row > div:last-child {
            width: 100%;
            margin-top: 8px;
            justify-content: flex-start;
        }
    }
</style>
@endsection

@section('content')
<div style="max-width:900px; margin:0 auto;">
    <div class="activity-header" style="margin-bottom:24px;">
        <div>
            <h1 style="font-size:22px; font-weight:800; color:#1e293b; margin-bottom:4px;">📋 Activity Log</h1>
            <p style="color:#64748b; font-size:13px;">Complete audit trail of all actions in your CRM.</p>
        </div>
    </div>

    <div style="background:#fff; border:1px solid #e2e8f0; border-radius:16px; overflow:hidden;">
        @forelse($logs as $log)
            <div class="activity-row" style="padding:16px 20px; border-bottom:1px solid #f8fafc;">
                <div style="width:36px; height:36px; border-radius:50%; flex-shrink:0; display:flex; align-items:center; justify-content:center; font-size:16px;
                    background: {{ match($log->action) {
                        'created' => '#dcfce7',
                        'deleted' => '#fff1f2',
                        'ai_triggered' => '#eef2ff',
                        default => '#f1f5f9'
                    } }}">
                    {{ match($log->action) {
                        'created' => '✅',
                        'deleted' => '🗑',
                        'ai_triggered' => '🤖',
                        'updated' => '✏️',
                        default => '🔔'
                    } }}
                </div>
                <div style="flex:1;">
                    <p style="font-size:13px; color:#1e293b; font-weight:500; margin-bottom:3px;">{{ $log->description }}</p>
                    <p style="font-size:11px; color:#94a3b8;">
                        <strong style="color:#475569;">{{ $log->user?->name ?? 'System' }}</strong>
                        &bull; {{ $log->created_at->format('d M Y, h:i A') }}
                        &bull; {{ $log->created_at->diffForHumans() }}
                        @if($log->ip_address) &bull; IP: {{ $log->ip_address }} @endif
                    </p>
                </div>
                <div style="display:flex; gap:8px; align-items:center;">
                    @if($log->entity_type)
                        <span style="background:#f1f5f9; color:#64748b; padding:3px 8px; border-radius:6px; font-size:10px; font-weight:700;">{{ $log->entity_type }}</span>
                    @endif
                    @php
                        $actionColors = ['created'=>['#dcfce7','#15803d'], 'deleted'=>['#fff1f2','#be123c'], 'ai_triggered'=>['#eef2ff','#6366f1'], 'updated'=>['#fefce8','#a16207']];
                        $c = $actionColors[$log->action] ?? ['#f1f5f9','#475569'];
                    @endphp
                    <span style="background:{{ $c[0] }}; color:{{ $c[1] }}; padding:3px 8px; border-radius:6px; font-size:10px; font-weight:700; text-transform:uppercase;">{{ $log->action }}</span>
                </div>
            </div>
        @empty
            <div style="padding:60px; text-align:center; color:#94a3b8;">
                <div style="font-size:40px; margin-bottom:12px;">📋</div>
                <p style="font-size:14px;">No activity recorded yet. Actions in the CRM will appear here.</p>
            </div>
        @endforelse
    </div>

    <div style="margin-top:16px;">
        {{ $logs->links() }}
    </div>
</div>
@endsection

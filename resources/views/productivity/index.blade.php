@extends('layouts.app')

@section('header_title', 'Team Productivity Hub')

@section('styles')
<style>
    .productivity-layout-grid {
        display: grid;
        grid-template-columns: 2fr 1fr;
        gap: 24px;
        align-items: start;
    }
    .leaderboard-panel {
        grid-column: span 2;
        display: flex;
        flex-direction: column;
        gap: 24px;
    }
    @media (max-width: 1024px) {
        .productivity-layout-grid {
            grid-template-columns: 1fr;
        }
        .leaderboard-panel {
            grid-column: span 1;
        }
    }
</style>
@endsection

@section('content')
<!-- Metric table Grid -->
<div class="productivity-layout-grid">
    
    <!-- Left Column: Productivity Leaderboard Table -->
    <div class="leaderboard-panel">
        <div class="card" style="padding:0; overflow:hidden;">
            <div style="padding:16px 20px; border-bottom:1px solid #f1f5f9; display:flex; align-items:center; justify-content:space-between; background:#fafbff;">
                <div>
                    <h3 style="font-size:14px; font-weight:700; color:#1e293b;">
                        <i class="fa-solid fa-gauge-high" style="color:#6366f1; margin-right:6px;"></i> Employee Sales & Communication Logs
                    </h3>
                    <p style="font-size:11px; color:#94a3b8; margin-top:2px;">Track live agent call analytics, meetings completed, and conversion metrics.</p>
                </div>
            </div>
            
            <div style="overflow-x:auto;">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th style="padding:12px 20px;">Representative</th>
                            <th style="padding:12px 20px; text-align:center;">Calls Tracked</th>
                            <th style="padding:12px 20px; text-align:center;">Meetings Done</th>
                            <th style="padding:12px 20px; text-align:center;">Leads Assigned</th>
                            <th style="padding:12px 20px; text-align:center;">Won Deals</th>
                            <th style="padding:12px 20px; text-align:right;">Conversion %</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($employeeStats as $stat)
                            <tr>
                                <td style="padding:16px 20px; font-weight:700; color:#1e293b;">
                                    {{ $stat['user']->name }}
                                </td>
                                <td style="padding:16px 20px; text-align:center; font-weight:600; color:#475569;">
                                    {{ $stat['calls'] }}
                                </td>
                                <td style="padding:16px 20px; text-align:center; font-weight:600; color:#475569;">
                                    {{ $stat['meetings'] }}
                                </td>
                                <td style="padding:16px 20px; text-align:center; font-weight:600; color:#475569;">
                                    {{ $stat['leads_handled'] }}
                                </td>
                                <td style="padding:16px 20px; text-align:center; font-weight:700; color:#059669;">
                                    {{ $stat['won'] }}
                                </td>
                                <td style="padding:16px 20px; text-align:right; font-weight:800; color:#4f46e5;">
                                    {{ $stat['conversion_ratio'] }}%
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" style="text-align:center; padding:32px; color:#94a3b8;">No productivity data available yet.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Right Column: Weekly AI Performance Report -->
    <div>
        <div class="card p-6" style="position:relative; overflow:hidden;">
            <div style="position:absolute; top:12px; right:12px; opacity:0.04; pointer-events:none;">
                <i class="fa-solid fa-wand-magic-sparkles" style="font-size:60px; color:#6366f1;"></i>
            </div>
            
            <div style="display:flex; align-items:center; justify-content:between; border-bottom:1px solid #f1f5f9; padding-bottom:12px; margin-bottom:16px; justify-content:space-between;">
                <h3 style="font-size:14px; font-weight:800; color:#1e293b; display:flex; align-items:center; gap:6px; margin:0;">
                    <i class="fa-solid fa-robot" style="color:#6366f1;"></i>
                    <span>AI Weekly Sales Report</span>
                </h3>
                
                <form action="{{ route('productivity.refresh-report') }}" method="POST" style="margin:0;">
                    @csrf
                    <button type="submit" class="btn btn-light btn-sm" style="font-size:10px !important; padding:4px 8px !important; color:#4f46e5;">
                        <i class="fa-solid fa-rotate"></i> Refresh
                    </button>
                </form>
            </div>

            <div style="display:flex; flex-direction:column; gap:20px; font-size:12px;">
                <!-- Overview -->
                <div style="background:#f8fafc; border:1px solid #e2e8f0; padding:14px; border-radius:12px;">
                    <span style="display:block; font-size:10px; color:#64748b; uppercase; font-weight:700; text-transform:uppercase; margin-bottom:6px;">Performance Overview</span>
                    <p style="color:#334155; line-height:1.6; margin:0;">{{ $reportData['weekly_summary'] ?? 'Aggregating weekly logs...' }}</p>
                </div>

                <!-- Top performers -->
                <div>
                    <span style="display:block; font-size:10px; color:#64748b; uppercase; font-weight:700; text-transform:uppercase; margin-bottom:8px;">Top Performers of the Week</span>
                    <div style="display:flex; flex-wrap:wrap; gap:8px;">
                        @if(isset($reportData['top_performers']) && is_array($reportData['top_performers']))
                            @forelse($reportData['top_performers'] as $perfName)
                                <span class="badge b-won" style="padding:4px 10px !important;">
                                    <i class="fa-solid fa-medal" style="margin-right:4px;"></i>
                                    <span>{{ $perfName }}</span>
                                </span>
                            @empty
                                <span style="color:#94a3b8; font-style:italic;">None detected yet.</span>
                            @endforelse
                        @else
                            <span style="color:#94a3b8; font-style:italic;">None detected yet.</span>
                        @endif
                    </div>
                </div>

                <!-- KPIs -->
                <div>
                    <span style="display:block; font-size:10px; color:#64748b; uppercase; font-weight:700; text-transform:uppercase; margin-bottom:8px;">KPI Benchmarks & Trends</span>
                    <ul style="list-style:none; padding:0; margin:0; display:flex; flex-direction:column; gap:8px;">
                        @if(isset($reportData['kpis']) && is_array($reportData['kpis']))
                            @forelse($reportData['kpis'] as $kpi)
                                <li style="display:flex; align-items:start; gap:8px; color:#475569; line-height:1.5;">
                                    <span style="width:6px; height:6px; border-radius:50%; background:#6366f1; margin-top:6px; flex-shrink:0;"></span>
                                    <span>{{ $kpi }}</span>
                                </li>
                            @empty
                                <li style="color:#94a3b8; font-style:italic;">No KPIs registered.</li>
                            @endforelse
                        @else
                            <li style="color:#94a3b8; font-style:italic;">No KPIs registered.</li>
                        @endif
                    </ul>
                </div>

                <!-- Improvement Suggestions -->
                <div style="padding-top:16px; border-top:1px solid #f1f5f9;">
                    <span style="display:block; font-size:10px; color:#64748b; uppercase; font-weight:700; text-transform:uppercase; margin-bottom:8px;">AI Coaching & Improvement Suggestions</span>
                    <ul style="list-style:none; padding:0; margin:0; display:flex; flex-direction:column; gap:8px;">
                        @if(isset($reportData['improvement_suggestions']) && is_array($reportData['improvement_suggestions']))
                            @forelse($reportData['improvement_suggestions'] as $suggestion)
                                <li style="display:flex; align-items:start; gap:8px; color:#475569; line-height:1.5;">
                                    <span style="width:6px; height:6px; border-radius:50%; background:#ef4444; margin-top:6px; flex-shrink:0;"></span>
                                    <span>{{ $suggestion }}</span>
                                </li>
                            @empty
                                <li style="color:#94a3b8; font-style:italic;">No suggestions available.</li>
                            @endforelse
                        @else
                            <li style="color:#94a3b8; font-style:italic;">No suggestions available.</li>
                        @endif
                    </ul>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection

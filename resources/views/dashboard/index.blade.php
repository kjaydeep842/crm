@extends('layouts.app')

@section('header_title', 'AURA Control Center')

@section('content')

{{-- ── KPI Cards ── --}}
<div style="display:grid;grid-template-columns:repeat(4,1fr);gap:16px;margin-bottom:24px;">

    {{-- Total Leads --}}
    <div class="card" style="padding:20px;border-left:3px solid #06b6d4;position:relative;overflow:hidden;transition:transform .2s;">
        <div style="position:absolute;top:-10px;right:-10px;font-size:60px;color:#06b6d4;opacity:.05;"><i class="fa-solid fa-users"></i></div>
        <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:12px;">
            <span style="font-size:10px;font-weight:700;text-transform:uppercase;letter-spacing:.07em;color:#64748b;">Total Pipeline Leads</span>
            <span style="width:32px;height:32px;border-radius:9px;background:#ecfeff;display:flex;align-items:center;justify-content:center;"><i class="fa-solid fa-users" style="color:#0891b2;font-size:12px;"></i></span>
        </div>
        <div style="font-size:28px;font-weight:800;color:#1e293b;line-height:1;">{{ $totalLeads }}</div>
        <div style="margin-top:12px;padding-top:10px;border-top:1px solid #f1f5f9;display:flex;justify-content:space-between;font-size:11px;color:#64748b;">
            <span>New: <b style="color:#1e293b;">{{ $newLeads }}</b></span>
            <span>Qualified: <b style="color:#047857;">{{ $qualifiedLeads }}</b></span>
        </div>
    </div>

    {{-- Meetings Scheduled --}}
    <div class="card" style="padding:20px;border-left:3px solid #8b5cf6;position:relative;overflow:hidden;">
        <div style="position:absolute;top:-10px;right:-10px;font-size:60px;color:#8b5cf6;opacity:.05;"><i class="fa-solid fa-calendar-check"></i></div>
        <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:12px;">
            <span style="font-size:10px;font-weight:700;text-transform:uppercase;letter-spacing:.07em;color:#64748b;">Meetings Scheduled</span>
            <span style="width:32px;height:32px;border-radius:9px;background:#f5f3ff;display:flex;align-items:center;justify-content:center;"><i class="fa-solid fa-calendar-days" style="color:#7c3aed;font-size:12px;"></i></span>
        </div>
        <div style="font-size:28px;font-weight:800;color:#1e293b;line-height:1;">{{ $meetingsScheduled }}</div>
        <div style="margin-top:12px;padding-top:10px;border-top:1px solid #f1f5f9;display:flex;justify-content:space-between;font-size:11px;color:#64748b;">
            <span>Completed: <b style="color:#1e293b;">{{ $meetingsCompleted }}</b></span>
            <span>Lost: <b style="color:#be123c;">{{ $lostLeads }}</b></span>
        </div>
    </div>

    {{-- Follow-ups --}}
    <div class="card" style="padding:20px;border-left:3px solid #f43f5e;position:relative;overflow:hidden;">
        <div style="position:absolute;top:-10px;right:-10px;font-size:60px;color:#f43f5e;opacity:.05;"><i class="fa-solid fa-bell"></i></div>
        <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:12px;">
            <span style="font-size:10px;font-weight:700;text-transform:uppercase;letter-spacing:.07em;color:#64748b;">Priority Follow-ups Today</span>
            <span style="width:32px;height:32px;border-radius:9px;background:#fff1f2;display:flex;align-items:center;justify-content:center;"><i class="fa-solid fa-bell" style="color:#be123c;font-size:12px;"></i></span>
        </div>
        <div style="font-size:28px;font-weight:800;color:#1e293b;line-height:1;">{{ $todayFollowups }}</div>
        <div style="margin-top:12px;padding-top:10px;border-top:1px solid #f1f5f9;display:flex;justify-content:space-between;font-size:11px;color:#64748b;">
            <span>Critical: <b style="color:#be123c;">{{ $todayFollowups }}</b></span>
            <span>Pending tasks: <b style="color:#1e293b;">{{ $priorityTasks->count() }}</b></span>
        </div>
    </div>

    {{-- Revenue Forecast --}}
    <div class="card" style="padding:20px;border-left:3px solid #10b981;position:relative;overflow:hidden;">
        <div style="position:absolute;top:-10px;right:-10px;font-size:60px;color:#10b981;opacity:.05;"><i class="fa-solid fa-indian-rupee-sign"></i></div>
        <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:12px;">
            <span style="font-size:10px;font-weight:700;text-transform:uppercase;letter-spacing:.07em;color:#64748b;">AI Revenue Forecast</span>
            <span style="width:32px;height:32px;border-radius:9px;background:#ecfdf5;display:flex;align-items:center;justify-content:center;"><i class="fa-solid fa-indian-rupee-sign" style="color:#047857;font-size:12px;"></i></span>
        </div>
        <div style="font-size:22px;font-weight:800;color:#047857;line-height:1;">₹{{ number_format($revenueForecast, 0) }}</div>
        <div style="margin-top:12px;padding-top:10px;border-top:1px solid #f1f5f9;font-size:11px;color:#64748b;">
            Weighted by AI buying-intent probability
        </div>
    </div>
</div>

{{-- ── Charts Row ── --}}
<div style="display:grid;grid-template-columns:1fr 1fr 1.5fr;gap:16px;margin-bottom:24px;">
    {{-- Lead Sources --}}
    <div class="card" style="padding:18px;">
        <h3 style="font-size:12px;font-weight:700;color:#1e293b;margin-bottom:14px;display:flex;align-items:center;gap:6px;">
            <i class="fa-solid fa-compass" style="color:#6366f1;"></i> Lead Sources
        </h3>
        <div style="height:160px;position:relative;"><canvas id="sourceChart"></canvas></div>
    </div>

    {{-- Inquiry Status --}}
    <div class="card" style="padding:18px;">
        <h3 style="font-size:12px;font-weight:700;color:#1e293b;margin-bottom:14px;display:flex;align-items:center;gap:6px;">
            <i class="fa-solid fa-filter" style="color:#06b6d4;"></i> Inquiry Status
        </h3>
        <div style="height:160px;position:relative;"><canvas id="statusChart"></canvas></div>
    </div>

    {{-- Monthly Conversion --}}
    <div class="card" style="padding:18px;">
        <h3 style="font-size:12px;font-weight:700;color:#1e293b;margin-bottom:14px;display:flex;align-items:center;gap:6px;">
            <i class="fa-solid fa-chart-bar" style="color:#8b5cf6;"></i> Monthly Conversion (Won vs Lost)
        </h3>
        <div style="height:160px;position:relative;"><canvas id="conversionChart"></canvas></div>
    </div>
</div>

{{-- ── Bottom Section ── --}}
<div style="display:grid;grid-template-columns:1fr 1fr 1fr;gap:16px;">

    {{-- Recent Leads --}}
    <div class="card" style="padding:0;overflow:hidden;grid-column:span 2;">
        <div style="padding:16px 18px;border-bottom:1px solid #f1f5f9;display:flex;align-items:center;justify-content:space-between;">
            <h3 style="font-size:13px;font-weight:700;color:#1e293b;display:flex;align-items:center;gap:6px;">
                <i class="fa-solid fa-users-viewfinder" style="color:#6366f1;"></i> Recent Pipelines
            </h3>
            <a href="{{ route('leads.index') }}" class="btn btn-sm btn-light">
                See all <i class="fa-solid fa-arrow-right" style="font-size:9px;"></i>
            </a>
        </div>
        <table class="data-table">
            <thead>
                <tr>
                    <th>Name & Company</th>
                    <th>Status</th>
                    <th style="text-align:right;">AI Score</th>
                    <th style="text-align:right;">Budget</th>
                    <th style="text-align:right;">Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse($recentLeads as $lead)
                <tr>
                    <td>
                        <span style="font-weight:700;color:#1e293b;display:block;">{{ $lead->full_name }}</span>
                        <span style="font-size:10px;color:#94a3b8;">{{ $lead->company_name ?: 'No Company' }}</span>
                    </td>
                    <td>
                        @php
                            $cls = match($lead->status) {
                                'New'=>'b-new','Contacted'=>'b-contacted','Qualified'=>'b-qualified',
                                'Won'=>'b-won','Lost'=>'b-lost',default=>'b-proposal'
                            };
                        @endphp
                        <span class="badge {{ $cls }}">{{ $lead->status }}</span>
                    </td>
                    <td style="text-align:right;">
                        <span style="display:inline-flex;align-items:center;justify-content:center;width:28px;height:28px;border-radius:50%;background:#eef2ff;color:#4f46e5;font-size:10px;font-weight:800;">{{ $lead->ai_score }}</span>
                    </td>
                    <td style="text-align:right;font-weight:700;color:#1e293b;">₹{{ number_format($lead->budget, 0) }}</td>
                    <td style="text-align:right;">
                        <a href="{{ route('leads.show', $lead->id) }}" class="btn btn-sm btn-light btn-icon">
                            <i class="fa-solid fa-chevron-right" style="font-size:10px;"></i>
                        </a>
                    </td>
                </tr>
                @empty
                <tr><td colspan="5" style="text-align:center;color:#94a3b8;padding:24px;">No leads logged yet.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Right Panel: Employee + Tasks --}}
    <div style="display:flex;flex-direction:column;gap:16px;">

        {{-- Employee Leaderboard --}}
        <div class="card" style="padding:16px;">
            <h3 style="font-size:12px;font-weight:700;color:#1e293b;margin-bottom:12px;display:flex;align-items:center;gap:6px;">
                <i class="fa-solid fa-medal" style="color:#b45309;"></i> Employee Leaderboard
            </h3>
            <div style="display:flex;flex-direction:column;gap:8px;">
                @foreach($employeePerformance as $idx => $perf)
                <div style="display:flex;align-items:center;justify-content:space-between;padding:10px 12px;border-radius:9px;background:#f8fafc;border:1px solid #f1f5f9;">
                    <div style="display:flex;align-items:center;gap:8px;">
                        <span style="width:22px;height:22px;border-radius:6px;background:#fffbeb;color:#b45309;font-size:10px;font-weight:800;display:flex;align-items:center;justify-content:center;">#{{ $idx+1 }}</span>
                        <div>
                            <span style="font-size:12px;font-weight:700;color:#1e293b;display:block;">{{ $perf['user']->name }}</span>
                            <span style="font-size:10px;color:#64748b;">Leads: <b>{{ $perf['leads_handled'] }}</b> | Won: <b>{{ $perf['won'] }}</b></span>
                        </div>
                    </div>
                    <div style="text-align:right;">
                        <span style="font-size:13px;font-weight:800;color:#047857;">{{ $perf['conversion_ratio'] }}%</span>
                        <span style="font-size:9px;color:#94a3b8;display:block;text-transform:uppercase;letter-spacing:.05em;">Conversion</span>
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        {{-- Priority Tasks --}}
        <div class="card" style="padding:16px;">
            <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:12px;">
                <h3 style="font-size:12px;font-weight:700;color:#1e293b;display:flex;align-items:center;gap:6px;">
                    <i class="fa-solid fa-list-check" style="color:#be123c;"></i> Tasks Console
                </h3>
                <a href="{{ route('tasks.index') }}" style="font-size:11px;color:#6366f1;font-weight:600;text-decoration:none;">Open <i class="fa-solid fa-arrow-right" style="font-size:9px;"></i></a>
            </div>
            <div style="display:flex;flex-direction:column;gap:7px;">
                @forelse($priorityTasks as $task)
                <div style="display:flex;align-items:flex-start;gap:9px;padding:9px 11px;border-radius:8px;background:#f8fafc;border:1px solid #f1f5f9;">
                    <form action="{{ route('tasks.toggle', $task->id) }}" method="POST" style="margin:0;flex-shrink:0;">
                        @csrf
                        <button type="submit" style="width:16px;height:16px;border-radius:4px;border:2px solid #cbd5e1;background:#fff;cursor:pointer;margin-top:1px;display:flex;align-items:center;justify-content:center;"></button>
                    </form>
                    <div>
                        <span style="font-size:11px;font-weight:600;color:#1e293b;display:block;">{{ Str::limit($task->title, 45) }}</span>
                        <div style="display:flex;align-items:center;gap:6px;margin-top:3px;">
                            @php $pc = match($task->priority){'High'=>'b-high','Medium'=>'b-medium',default=>'b-low'}; @endphp
                            <span class="badge {{ $pc }}">{{ $task->priority }}</span>
                            <span style="font-size:10px;color:#94a3b8;">Due: {{ date('d M', strtotime($task->due_date)) }}</span>
                        </div>
                    </div>
                </div>
                @empty
                <p style="text-align:center;color:#94a3b8;font-size:12px;padding:12px 0;">No pending tasks.</p>
                @endforelse
            </div>
        </div>

        {{-- Upcoming Meetings --}}
        <div class="card" style="padding:16px;">
            <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:12px;">
                <h3 style="font-size:12px;font-weight:700;color:#1e293b;display:flex;align-items:center;gap:6px;">
                    <i class="fa-solid fa-handshake-simple" style="color:#7c3aed;"></i> Upcoming Meetings
                </h3>
                <a href="{{ route('meetings.index') }}" style="font-size:11px;color:#6366f1;font-weight:600;text-decoration:none;">See all <i class="fa-solid fa-arrow-right" style="font-size:9px;"></i></a>
            </div>
            <div style="display:flex;flex-direction:column;gap:7px;">
                @forelse($upcomingMeetings as $m)
                <div style="display:flex;align-items:center;justify-content:space-between;padding:9px 11px;border-radius:8px;background:#f8fafc;border:1px solid #f1f5f9;">
                    <div style="display:flex;align-items:center;gap:8px;">
                        <span style="width:28px;height:28px;border-radius:7px;background:#f5f3ff;display:flex;align-items:center;justify-content:center;flex-shrink:0;"><i class="fa-solid fa-calendar" style="color:#7c3aed;font-size:11px;"></i></span>
                        <div>
                            <span style="font-size:11px;font-weight:600;color:#1e293b;display:block;">{{ Str::limit($m->title, 28) }}</span>
                            <span style="font-size:10px;color:#64748b;">{{ $m->customer_name }}</span>
                        </div>
                    </div>
                    <div style="text-align:right;flex-shrink:0;">
                        <span style="font-size:10px;font-weight:700;color:#7c3aed;display:block;">{{ date('d M', strtotime($m->date)) }}</span>
                        <span style="font-size:10px;color:#94a3b8;">{{ date('h:i A', strtotime($m->time)) }}</span>
                    </div>
                </div>
                @empty
                <p style="text-align:center;color:#94a3b8;font-size:12px;padding:12px 0;">No meetings scheduled.</p>
                @endforelse
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener("DOMContentLoaded", function () {
    const chartDefaults = {
        responsive: true, maintainAspectRatio: false,
    };
    const legendStyle = { color: '#475569', font: { size: 10, family: 'Inter' }, padding: 8 };

    // Lead Sources
    new Chart(document.getElementById('sourceChart'), {
        type: 'doughnut',
        data: {
            labels: Object.keys(@json($sourceData)),
            datasets: [{ data: Object.values(@json($sourceData)), backgroundColor: ['#6366F1','#06B6D4','#8B5CF6','#F43F5E','#10B981','#64748B'], borderWidth: 0 }]
        },
        options: { ...chartDefaults, plugins: { legend: { position: 'bottom', labels: legendStyle } } }
    });

    // Inquiry Status
    new Chart(document.getElementById('statusChart'), {
        type: 'pie',
        data: {
            labels: Object.keys(@json($inquiryStatus)),
            datasets: [{ data: Object.values(@json($inquiryStatus)), backgroundColor: ['#EF4444','#10B981'], borderWidth: 0 }]
        },
        options: { ...chartDefaults, plugins: { legend: { position: 'bottom', labels: legendStyle } } }
    });

    // Monthly Conversion
    const convRaw = @json($monthlyConversions);
    const months = Object.keys(convRaw).length ? Object.keys(convRaw) : ['—'];
    new Chart(document.getElementById('conversionChart'), {
        type: 'bar',
        data: {
            labels: months,
            datasets: [
                { label: 'Won', data: months.map(m => convRaw[m]?.Won || 0), backgroundColor: '#10B981', borderRadius: 4 },
                { label: 'Lost', data: months.map(m => convRaw[m]?.Lost || 0), backgroundColor: '#F43F5E', borderRadius: 4 }
            ]
        },
        options: {
            ...chartDefaults,
            scales: {
                x: { grid: { display: false }, ticks: { color: '#475569', font: { size: 10 } } },
                y: { grid: { color: 'rgba(0,0,0,0.05)' }, ticks: { color: '#475569', font: { size: 10 } } }
            },
            plugins: { legend: { position: 'top', labels: legendStyle } }
        }
    });
});
</script>
@endsection

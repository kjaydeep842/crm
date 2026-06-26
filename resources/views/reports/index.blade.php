@extends('layouts.app')

@section('header_title', 'Analytics & Reports Hub')

@section('styles')
<style>
    .forecast-header-card {
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    @media (max-width: 576px) {
        .forecast-header-card {
            flex-direction: column;
            align-items: flex-start;
            gap: 16px;
        }
        .forecast-header-card > div:last-child {
            text-align: left !important;
        }
    }
</style>
@endsection

@section('content')
<div x-data="{ activeTab: 'leads' }">

    <!-- Tab Bar Selector -->
    <div style="display:flex; flex-wrap:wrap; items-center; gap:8px; margin-bottom:32px; border-bottom:1px solid #e2e8f0; padding-bottom:1px;">
        <button @click="activeTab = 'leads'" :style="activeTab === 'leads' ? 'border-bottom:2px solid #6366f1; color:#4f46e5; font-weight:700;' : 'border-bottom:2px solid transparent; color:#64748b; font-weight:600;'" class="px-5 py-3 text-xs transition-all" style="background:none; border:none; cursor:pointer;">
            <i class="fa-solid fa-users" style="margin-right:6px;"></i> Lead Reports
        </button>
        <button @click="activeTab = 'inquiries'" :style="activeTab === 'inquiries' ? 'border-bottom:2px solid #6366f1; color:#4f46e5; font-weight:700;' : 'border-bottom:2px solid transparent; color:#64748b; font-weight:600;'" class="px-5 py-3 text-xs transition-all" style="background:none; border:none; cursor:pointer;">
            <i class="fa-solid fa-inbox" style="margin-right:6px;"></i> Inquiry Reports
        </button>
        <button @click="activeTab = 'conversion'" :style="activeTab === 'conversion' ? 'border-bottom:2px solid #6366f1; color:#4f46e5; font-weight:700;' : 'border-bottom:2px solid transparent; color:#64748b; font-weight:600;'" class="px-5 py-3 text-xs transition-all" style="background:none; border:none; cursor:pointer;">
            <i class="fa-solid fa-chart-line" style="margin-right:6px;"></i> Conversion Analysis
        </button>
        <button @click="activeTab = 'employee'" :style="activeTab === 'employee' ? 'border-bottom:2px solid #6366f1; color:#4f46e5; font-weight:700;' : 'border-bottom:2px solid transparent; color:#64748b; font-weight:600;'" class="px-5 py-3 text-xs transition-all" style="background:none; border:none; cursor:pointer;">
            <i class="fa-solid fa-medal" style="margin-right:6px;"></i> Employee Standings
        </button>
        <button @click="activeTab = 'forecast'" :style="activeTab === 'forecast' ? 'border-bottom:2px solid #6366f1; color:#4f46e5; font-weight:700;' : 'border-bottom:2px solid transparent; color:#64748b; font-weight:600;'" class="px-5 py-3 text-xs transition-all" style="background:none; border:none; cursor:pointer;">
            <i class="fa-solid fa-wallet" style="margin-right:6px;"></i> Revenue Forecast
        </button>
        <button @click="activeTab = 'meetings'" :style="activeTab === 'meetings' ? 'border-bottom:2px solid #6366f1; color:#4f46e5; font-weight:700;' : 'border-bottom:2px solid transparent; color:#64748b; font-weight:600;'" class="px-5 py-3 text-xs transition-all" style="background:none; border:none; cursor:pointer;">
            <i class="fa-solid fa-calendar" style="margin-right:6px;"></i> Meeting Stats
        </button>
    </div>

    <!-- 1. Lead Reports Tab -->
    <div x-show="activeTab === 'leads'" style="display: none; display:flex; flex-direction:column; gap:24px;">
        <div style="display:grid; grid-template-columns: repeat(auto-fit, minmax(320px, 1fr)); gap:24px;">
            <!-- Sources -->
            <div class="card p-6">
                <h3 style="font-size:14px; font-weight:700; color:#1e293b; margin-bottom:16px; display:flex; align-items:center; gap:8px;"><i class="fa-solid fa-compass" style="color:#6366f1;"></i> Pipeline Sources Summary</h3>
                <div style="overflow-x:auto;">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>Source Channel</th>
                                <th style="text-align:center;">Leads Captured</th>
                                <th style="text-align:right;">Total Allocated Budget</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($leadSources as $src)
                                <tr>
                                    <td style="font-weight:700;">{{ $src->lead_source }}</td>
                                    <td style="text-align:center; font-weight:700;">{{ $src->count }}</td>
                                    <td style="text-align:right; font-weight:700; color:#0f766e;">₹{{ number_format($src->total_budget, 2) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Statuses -->
            <div class="card p-6">
                <h3 style="font-size:14px; font-weight:700; color:#1e293b; margin-bottom:16px; display:flex; align-items:center; gap:8px;"><i class="fa-solid fa-chart-pie" style="color:#06b6d4;"></i> Pipeline Status Breakdown</h3>
                <div style="overflow-x:auto;">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>Sales Stage</th>
                                <th style="text-align:center;">Total Count</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($leadStatuses as $stat)
                                <tr>
                                    <td style="font-weight:700;">{{ $stat->status }}</td>
                                    <td style="text-align:center; font-weight:700; color:#4f46e5;">{{ $stat->count }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- 2. Inquiry Reports Tab -->
    <div x-show="activeTab === 'inquiries'" style="display: none;">
        <div class="card p-6">
            <h3 style="font-size:14px; font-weight:700; color:#1e293b; margin-bottom:16px; display:flex; align-items:center; gap:8px;"><i class="fa-solid fa-inbox" style="color:#6366f1;"></i> Omnichannel Ingestion Conversion Summary</h3>
            <div style="overflow-x:auto;">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Inbound Channel</th>
                            <th style="text-align:center;">Inquiries Logged</th>
                            <th style="text-align:center;">Converted to Leads</th>
                            <th style="text-align:right;">Conversion Ratio</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($inquirySources as $is)
                            <tr>
                                <td style="font-weight:700;">{{ $is->source }}</td>
                                <td style="text-align:center; font-weight:700;">{{ $is->count }}</td>
                                <td style="text-align:center; font-weight:700; color:#059669;">{{ $is->processed }}</td>
                                <td style="text-align:right; font-weight:700; color:#4f46e5;">
                                    {{ $is->count > 0 ? round(($is->processed / $is->count) * 100, 1) : 0 }}%
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- 3. Conversion Analysis Tab -->
    <div x-show="activeTab === 'conversion'" style="display: none; display:flex; flex-direction:column; gap:24px;">
        <div style="display:grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap:20px;">
            <!-- KPIs -->
            <div class="card p-5 text-center">
                <span style="font-size:9px; color:#64748b; text-transform:uppercase; font-weight:700; display:block; margin-bottom:4px;">Total Pipeline</span>
                <span style="font-size:28px; font-weight:800; color:#1e293b;">{{ $totalLeads }}</span>
            </div>
            <div class="card p-5 text-center">
                <span style="font-size:9px; color:#64748b; text-transform:uppercase; font-weight:700; display:block; margin-bottom:4px;">Won Pipelines</span>
                <span style="font-size:28px; font-weight:800; color:#059669;">{{ $wonLeads }}</span>
            </div>
            <div class="card p-5 text-center">
                <span style="font-size:9px; color:#64748b; text-transform:uppercase; font-weight:700; display:block; margin-bottom:4px;">Lost Pipelines</span>
                <span style="font-size:28px; font-weight:800; color:#be123c;">{{ $lostLeads }}</span>
            </div>
        </div>

        <div class="card p-6">
            <h3 style="font-size:14px; font-weight:700; color:#1e293b; margin-bottom:16px;">Overall Global Conversion Rate</h3>
            <div style="display:flex; align-items:center; gap:16px;">
                <span style="font-size:32px; font-weight:900; color:#4f46e5;">{{ $conversionRate }}%</span>
                <div style="flex:1; height:12px; border-radius:999px; background:#f1f5f9; overflow:hidden; border:1px solid #e2e8f0;">
                    <div style="height:100%; background:linear-gradient(135deg, #6366f1, #06b6d4); width: {{ $conversionRate }}%"></div>
                </div>
            </div>
        </div>
    </div>

    <!-- 4. Employee Standings Tab -->
    <div x-show="activeTab === 'employee'" style="display: none;">
        <div class="card p-6">
            <h3 style="font-size:14px; font-weight:700; color:#1e293b; margin-bottom:16px; display:flex; align-items:center; gap:8px;"><i class="fa-solid fa-medal" style="color:#d97706;"></i> Individual Performance Standings</h3>
            <div style="overflow-x:auto;">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Sales Agent</th>
                            <th style="text-align:center;">Leads Handled</th>
                            <th style="text-align:center;">Won Count</th>
                            <th style="text-align:center;">Lost Count</th>
                            <th style="text-align:right;">Conversion Ratio</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($employeeStats as $es)
                            <tr>
                                <td style="font-weight:700;">{{ $es['name'] }}</td>
                                <td style="text-align:center;">{{ $es['handled'] }}</td>
                                <td style="text-align:center; font-weight:700; color:#059669;">{{ $es['won'] }}</td>
                                <td style="text-align:center; color:#be123c;">{{ $es['lost'] }}</td>
                                <td style="text-align:right; font-weight:700; color:#4f46e5;">{{ $es['rate'] }}%</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- 5. Weighted Revenue Forecast Tab -->
    <div x-show="activeTab === 'forecast'" style="display: none; display:flex; flex-direction:column; gap:24px;">
        <!-- Total Forecast KPI -->
        <div class="card p-6 forecast-header-card" style="border-radius:16px;">
            <div>
                <h3 style="font-size:14px; font-weight:700; color:#1e293b; margin-bottom:4px;">AI Weighted Sales Forecast</h3>
                <p style="font-size:11px; color:#64748b;">Weighted totals (Deal budget multiplied by AI intent probability).</p>
            </div>
            
            <div style="text-align:right;">
                <span style="font-size:9px; color:#64748b; uppercase; font-weight:700; display:block; margin-bottom:2px;">Grand Forecast</span>
                <span style="font-size:28px; font-weight:800; color:#059669;">₹{{ number_format($totalForecastValue, 2) }}</span>
            </div>
        </div>

        <div class="card p-6">
            <h3 style="font-size:14px; font-weight:700; color:#1e293b; margin-bottom:16px; display:flex; align-items:center; gap:8px;"><i class="fa-solid fa-filter-circle-dollar" style="color:#6366f1;"></i> Pipeline Forecast Details</h3>
            <div style="overflow-x:auto;">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Lead & Company</th>
                            <th>Sales Stage</th>
                            <th style="text-align:center;">AI Probability</th>
                            <th style="text-align:right;">Raw Deal Value</th>
                            <th style="text-align:right;">Weighted Forecast</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($forecastLeads as $f)
                            <tr>
                                <td>
                                    <span style="font-weight:700; display:block;">{{ $f->full_name }}</span>
                                    <span style="font-size:10px; color:#94a3b8; display:block; margin-top:2px;">{{ $f->company_name ?: 'No Company' }}</span>
                                </td>
                                <td>
                                    <span class="badge b-proposal">{{ $f->status }}</span>
                                </td>
                                <td style="text-align:center; font-weight:700; color:#4f46e5;">{{ $f->ai_sales_probability ?? 50 }}%</td>
                                <td style="text-align:right; font-weight:700;">₹{{ number_format($f->budget, 2) }}</td>
                                <td style="text-align:right; font-weight:700; color:#059669;">₹{{ number_format($f->budget * (($f->ai_sales_probability ?? 50)/100), 2) }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" style="text-align:center; padding:32px; color:#94a3b8;">No active pipelines found for forecasting.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- 6. Meeting Stats Tab -->
    <div x-show="activeTab === 'meetings'" style="display: none;">
        <div style="display:grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap:20px;">
            <div class="card p-5 text-center">
                <span style="font-size:9px; color:#64748b; text-transform:uppercase; font-weight:700; display:block; margin-bottom:4px;">Total Meetings</span>
                <span style="font-size:28px; font-weight:800; color:#1e293b;">{{ $meetingsTotal }}</span>
            </div>
            <div class="card p-5 text-center">
                <span style="font-size:9px; color:#64748b; text-transform:uppercase; font-weight:700; display:block; margin-bottom:4px;">Completed Meetings</span>
                <span style="font-size:28px; font-weight:800; color:#059669;">{{ $meetingsCompleted }}</span>
            </div>
            <div class="card p-5 text-center">
                <span style="font-size:9px; color:#64748b; text-transform:uppercase; font-weight:700; display:block; margin-bottom:4px;">Scheduled / Upcoming</span>
                <span style="font-size:28px; font-weight:800; color:#6366f1;">{{ $meetingsScheduled }}</span>
            </div>
        </div>
    </div>

</div>
@endsection

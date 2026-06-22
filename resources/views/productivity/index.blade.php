@extends('layouts.app')

@section('header_title', 'Team Productivity Hub')

@section('content')
<!-- Metric table Grid -->
<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
    
    <!-- Left Column: Productivity Leaderboard Table -->
    <div class="lg:col-span-2 space-y-6">
        <div class="glass-card rounded-2xl p-6">
            <h3 class="text-sm font-bold text-white mb-4 flex items-center gap-2">
                <i class="fa-solid fa-gauge-high text-indigo-400"></i>
                <span>Employee Sales & Communication Logs</span>
            </h3>
            
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="border-b border-slate-800 bg-[#0C1220]/30 text-[10px] uppercase font-bold text-slate-400 tracking-wider">
                            <th class="py-4 px-6">Representative</th>
                            <th class="py-4 px-6 text-center">Calls Tracked</th>
                            <th class="py-4 px-6 text-center">Meetings Done</th>
                            <th class="py-4 px-6 text-center">Leads Assigned</th>
                            <th class="py-4 px-6 text-center">Won Deals</th>
                            <th class="py-4 px-6 text-right">Conversion %</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-800/40 text-xs text-slate-350">
                        @foreach($employeeStats as $stat)
                            <tr class="hover:bg-slate-900/10 transition-colors">
                                <td class="py-4 px-6 font-bold text-white">
                                    {{ $stat['user']->name }}
                                </td>
                                <td class="py-4 px-6 text-center font-semibold text-slate-300">
                                    {{ $stat['calls'] }}
                                </td>
                                <td class="py-4 px-6 text-center font-semibold text-slate-300">
                                    {{ $stat['meetings'] }}
                                </td>
                                <td class="py-4 px-6 text-center font-semibold text-slate-300">
                                    {{ $stat['leads_handled'] }}
                                </td>
                                <td class="py-4 px-6 text-center font-bold text-emerald-450">
                                    {{ $stat['won'] }}
                                </td>
                                <td class="py-4 px-6 text-right font-extrabold text-indigo-400">
                                    {{ $stat['conversion_ratio'] }}%
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Right Column: Weekly AI Performance Report -->
    <div class="space-y-6">
        <div class="glass-card rounded-2xl p-6 glow-violet relative border-indigo-500/20">
            <div class="absolute top-0 right-0 p-4 opacity-5">
                <i class="fa-solid fa-wand-magic-sparkles text-6xl text-indigo-400"></i>
            </div>
            
            <div class="flex items-center justify-between border-b border-slate-800 pb-3 mb-5">
                <h3 class="text-sm font-extrabold text-white flex items-center gap-1.5">
                    <i class="fa-solid fa-robot text-indigo-400"></i>
                    <span>AI Weekly Sales Report</span>
                </h3>
                
                <form action="{{ route('productivity.refresh-report') }}" method="POST">
                    @csrf
                    <button type="submit" class="text-[10px] font-bold text-indigo-400 hover:text-indigo-300 flex items-center gap-1">
                        <i class="fa-solid fa-rotate text-[9px]"></i> Refresh
                    </button>
                </form>
            </div>

            <div class="space-y-5 text-xs">
                <!-- Overview -->
                <div class="bg-slate-950/40 p-4 rounded-xl border border-slate-850">
                    <span class="text-[9px] text-slate-500 uppercase font-bold block mb-1">Performance Overview</span>
                    <p class="text-slate-350 leading-relaxed">{{ $reportData['weekly_summary'] ?? 'Aggregating weekly logs...' }}</p>
                </div>

                <!-- Top performers -->
                <div>
                    <span class="text-[9px] text-slate-500 uppercase font-bold block mb-2">Top Performers of the Week</span>
                    <div class="flex flex-wrap gap-2">
                        @if(isset($reportData['top_performers']) && is_array($reportData['top_performers']))
                            @foreach($reportData['top_performers'] as $perfName)
                                <span class="px-2.5 py-1 rounded-full bg-emerald-500/10 border border-emerald-500/20 text-emerald-450 font-bold text-[10px] flex items-center gap-1">
                                    <i class="fa-solid fa-medal"></i>
                                    <span>{{ $perfName }}</span>
                                </span>
                            @endforeach
                        @else
                            <span class="text-slate-500">None detected yet.</span>
                        @endif
                    </div>
                </div>

                <!-- KPIs -->
                <div>
                    <span class="text-[9px] text-slate-500 uppercase font-bold block mb-2">KPI Benchmarks & Trends</span>
                    <ul class="space-y-2">
                        @if(isset($reportData['kpis']) && is_array($reportData['kpis']))
                            @foreach($reportData['kpis'] as $kpi)
                                <li class="flex items-start gap-2 text-slate-350 leading-relaxed">
                                    <span class="w-1.5 h-1.5 rounded-full bg-indigo-500 mt-1.5 flex-shrink-0"></span>
                                    <span>{{ $kpi }}</span>
                                </li>
                            @endforeach
                        @else
                            <li class="text-slate-500">No KPIs registered.</li>
                        @endif
                    </ul>
                </div>

                <!-- Improvement Suggestions -->
                <div class="pt-4 border-t border-slate-850">
                    <span class="text-[9px] text-slate-500 uppercase font-bold block mb-2">AI Coaching & Improvement Suggestions</span>
                    <ul class="space-y-2">
                        @if(isset($reportData['improvement_suggestions']) && is_array($reportData['improvement_suggestions']))
                            @foreach($reportData['improvement_suggestions'] as $suggestion)
                                <li class="flex items-start gap-2 text-slate-350 leading-relaxed">
                                    <span class="w-1.5 h-1.5 rounded-full bg-rose-500 mt-1.5 flex-shrink-0"></span>
                                    <span>{{ $suggestion }}</span>
                                </li>
                            @endforeach
                        @else
                            <li class="text-slate-500">No suggestions available.</li>
                        @endif
                    </ul>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection

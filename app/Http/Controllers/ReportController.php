<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Lead;
use App\Models\Inquiry;
use App\Models\Meeting;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class ReportController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // Build scoping base queries
        $leadQuery = Lead::query();
        $inquiryQuery = Inquiry::query();
        $meetingQuery = Meeting::query();

        if ($user->isSuperAdmin()) {
            $employees = User::whereIn('role', ['sales', 'staff'])->get();
        } elseif ($user->isAdmin()) {
            $orgUserIds = User::where('organization_id', $user->organization_id)->pluck('id');
            
            $leadQuery->whereIn('assigned_to', $orgUserIds);
            $inquiryQuery->whereIn('assigned_to', $orgUserIds);
            $meetingQuery->whereHas('lead', function($q) use ($orgUserIds) {
                $q->whereIn('assigned_to', $orgUserIds);
            });
            
            $employees = User::where('organization_id', $user->organization_id)->whereIn('role', ['sales', 'staff'])->get();
        } else {
            $leadQuery->where('assigned_to', $user->id);
            $inquiryQuery->where('assigned_to', $user->id);
            $meetingQuery->whereHas('lead', function($q) use ($user) {
                $q->where('assigned_to', $user->id);
            });
            
            $employees = User::where('id', $user->id)->get();
        }

        // 1. Lead Source Summary
        $leadSources = (clone $leadQuery)
            ->selectRaw('lead_source, count(*) as count, sum(budget) as total_budget')
            ->groupBy('lead_source')
            ->get();

        // 2. Lead Status Summary
        $leadStatuses = (clone $leadQuery)
            ->selectRaw('status, count(*) as count')
            ->groupBy('status')
            ->get();

        // 3. Inquiry Source Summary
        $inquirySources = (clone $inquiryQuery)
            ->selectRaw('source, count(*) as count, sum(case when status="Processed" then 1 else 0 end) as processed')
            ->groupBy('source')
            ->get();

        // 4. Conversion Analysis
        $totalLeads = (clone $leadQuery)->count();
        $wonLeads = (clone $leadQuery)->where('status', 'Won')->count();
        $lostLeads = (clone $leadQuery)->where('status', 'Lost')->count();
        $conversionRate = $totalLeads > 0 ? round(($wonLeads / $totalLeads) * 100, 1) : 0;

        // 5. Employee Performance
        $employeeStats = [];
        foreach ($employees as $emp) {
            $handled = Lead::where('assigned_to', $emp->id)->count();
            $won = Lead::where('assigned_to', $emp->id)->where('status', 'Won')->count();
            $lost = Lead::where('assigned_to', $emp->id)->where('status', 'Lost')->count();
            $rate = $handled > 0 ? round(($won / $handled) * 100, 1) : 0;
            $employeeStats[] = [
                'name' => $emp->name,
                'handled' => $handled,
                'won' => $won,
                'lost' => $lost,
                'rate' => $rate
            ];
        }

        // 6. Revenue Forecast Details
        $forecastLeads = (clone $leadQuery)
            ->whereIn('status', ['Qualified', 'Proposal Sent', 'Negotiation'])
            ->orderBy('ai_sales_probability', 'desc')
            ->get();
        
        $totalForecastValue = 0;
        foreach ($forecastLeads as $lead) {
            $totalForecastValue += ($lead->budget * (($lead->ai_sales_probability ?? 50) / 100));
        }

        // 7. Meetings Summary
        $meetingsTotal = (clone $meetingQuery)->count();
        $meetingsCompleted = (clone $meetingQuery)->where('status', 'Completed')->count();
        $meetingsScheduled = (clone $meetingQuery)->where('status', 'Scheduled')->count();

        return view('reports.index', compact(
            'leadSources',
            'leadStatuses',
            'inquirySources',
            'totalLeads',
            'wonLeads',
            'lostLeads',
            'conversionRate',
            'employeeStats',
            'forecastLeads',
            'totalForecastValue',
            'meetingsTotal',
            'meetingsCompleted',
            'meetingsScheduled'
        ));
    }
}

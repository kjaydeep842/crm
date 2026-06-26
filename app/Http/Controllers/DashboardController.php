<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Lead;
use App\Models\Inquiry;
use App\Models\Meeting;
use App\Models\Task;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        if (!$user) {
            return redirect()->route('login');
        }

        // Setup base queries with multitenancy scoping
        $leadsQuery = Lead::query();
        $meetingsQuery = Meeting::query();
        $tasksQuery = Task::query();
        $inquiryQuery = Inquiry::query();

        if ($user->isSuperAdmin()) {
            // SuperAdmin sees all
        } elseif ($user->isAdmin()) {
            // Org Admin sees only their organization's records
            $orgUserIds = User::where('organization_id', $user->organization_id)->pluck('id');
            
            $leadsQuery->whereIn('assigned_to', $orgUserIds);
            $meetingsQuery->whereHas('lead', function ($q) use ($orgUserIds) {
                $q->whereIn('assigned_to', $orgUserIds);
            });
            $tasksQuery->whereIn('user_id', $orgUserIds);
            $inquiryQuery->whereIn('assigned_to', $orgUserIds);
        } else {
            // Staff sees only their assigned records
            $leadsQuery->where('assigned_to', $user->id);
            $meetingsQuery->whereHas('lead', function ($q) use ($user) {
                $q->where('assigned_to', $user->id);
            });
            $tasksQuery->where('user_id', $user->id);
            $inquiryQuery->where('assigned_to', $user->id);
        }

        $totalLeads = $leadsQuery->count();
        $newLeads = (clone $leadsQuery)->where('status', 'New')->count();
        $qualifiedLeads = (clone $leadsQuery)->where('status', 'Qualified')->count();
        $lostLeads = (clone $leadsQuery)->where('status', 'Lost')->count();

        $meetingsScheduled = (clone $meetingsQuery)->where('status', 'Scheduled')->count();
        $meetingsCompleted = (clone $meetingsQuery)->where('status', 'Completed')->count();

        $todayFollowups = (clone $tasksQuery)
            ->where('type', 'Follow-up')
            ->where('status', 'Pending')
            ->where('due_date', '<=', date('Y-m-d'))
            ->count();

        // Revenue Forecast: Sum of budgets for Leads in 'Qualified', 'Proposal Sent', 'Negotiation' * probability
        $forecastLeads = (clone $leadsQuery)
            ->whereIn('status', ['Qualified', 'Proposal Sent', 'Negotiation'])
            ->get();
        
        $revenueForecast = 0;
        foreach ($forecastLeads as $fLead) {
            $probability = $fLead->ai_sales_probability ?? 50;
            $budget = $fLead->budget ?? 0;
            $revenueForecast += $budget * ($probability / 100);
        }

        // Employee performance stats (scoped to the admin's organization if Org Admin)
        $empQuery = User::whereIn('role', ['sales', 'staff']);
        if ($user->isAdmin()) {
            $empQuery->where('organization_id', $user->organization_id);
        }
        $employees = $empQuery->get();

        $employeePerformance = [];
        foreach ($employees as $emp) {
            $empLeadsCount = Lead::where('assigned_to', $emp->id)->count();
            $empWonCount = Lead::where('assigned_to', $emp->id)->where('status', 'Won')->count();
            $empMeetingsCount = Meeting::whereHas('lead', function($q) use ($emp) {
                $q->where('assigned_to', $emp->id);
            })->count();

            $ratio = $empLeadsCount > 0 ? round(($empWonCount / $empLeadsCount) * 100, 1) : 0;
            $employeePerformance[] = [
                'user' => $emp,
                'leads_handled' => $empLeadsCount,
                'won' => $empWonCount,
                'meetings' => $empMeetingsCount,
                'conversion_ratio' => $ratio
            ];
        }

        // Chart 1: Lead Source Chart
        $sourceData = (clone $leadsQuery)->selectRaw('lead_source, count(*) as count')
            ->groupBy('lead_source')
            ->pluck('count', 'lead_source')
            ->toArray();

        // Chart 2: Monthly Conversion Chart
        $conversions = (clone $leadsQuery)->selectRaw('MONTHNAME(created_at) as month, status, count(*) as count')
            ->whereIn('status', ['Won', 'Lost'])
            ->groupBy('month', 'status')
            ->get();
        
        $monthlyConversions = [];
        foreach ($conversions as $c) {
            $monthlyConversions[$c->month][$c->status] = $c->count;
        }

        // Chart 3: Inquiry Status Chart
        $inquiryStatus = (clone $inquiryQuery)->selectRaw('status, count(*) as count')
            ->groupBy('status')
            ->pluck('count', 'status')
            ->toArray();

        // Get recent tasks, leads, and meetings for the lists
        $recentLeads = (clone $leadsQuery)->latest()->take(5)->get();
        $upcomingMeetings = (clone $meetingsQuery)->where('status', 'Scheduled')->orderBy('date')->orderBy('time')->take(5)->get();
        $priorityTasks = (clone $tasksQuery)->where('status', 'Pending')->orderBy('due_date')->take(5)->get();

        // All users list for sandbox user switching
        $allUsers = User::all();

        $org = $user->organization;
        if (!$org && $user->isSuperAdmin()) {
            $org = \App\Models\Organization::first();
        }

        $currentPackage = $org ? ucfirst($org->package) : 'Unknown';
        $aiCreditsUsed = $org ? $org->ai_credits_used : 0;
        $aiCreditLimit = $org ? $org->ai_credit_limit : 0;
        $usersCount = $org ? $org->users()->count() : 0;
        $maxUsers = $org ? $org->max_users : 0;

        return view('dashboard.index', compact(
            'currentPackage',
            'aiCreditsUsed',
            'aiCreditLimit',
            'usersCount',
            'maxUsers',
            'totalLeads',
            'newLeads',
            'qualifiedLeads',
            'lostLeads',
            'meetingsScheduled',
            'meetingsCompleted',
            'todayFollowups',
            'revenueForecast',
            'employeePerformance',
            'sourceData',
            'monthlyConversions',
            'inquiryStatus',
            'recentLeads',
            'upcomingMeetings',
            'priorityTasks',
            'allUsers'
        ));
    }

    public function switchUser($id)
    {
        $user = User::find($id);
        if ($user) {
            Auth::login($user);
        }
        return redirect()->back()->with('success', 'Switched user to ' . $user->name);
    }
}

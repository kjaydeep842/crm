<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Organization;
use App\Models\ActivityLog;
use App\Models\NotificationLog;
use App\Models\EmailTemplate;
use App\Models\Lead;
use App\Models\Task;
use App\Models\Meeting;

class OrganizationSettingsController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        if (!$user->isAdmin() && !$user->isSuperAdmin()) {
            abort(403, 'Only admins can access organization settings.');
        }
        $org = $user->isSuperAdmin() ? Organization::first() : $user->organization;
        $emailTemplates = EmailTemplate::where('organization_id', $org->id)->get();
        $activityLogs = ActivityLog::where('organization_id', $org->id)
            ->with('user')->latest()->limit(50)->get();

        return view('settings.organization', compact('org', 'emailTemplates', 'activityLogs'));
    }

    public function update(Request $request)
    {
        $user = Auth::user();
        if (!$user->isAdmin() && !$user->isSuperAdmin()) {
            abort(403);
        }

        $org = $user->isSuperAdmin() ? Organization::first() : $user->organization;

        $data = $request->validate([
            'name' => 'required|string|max:255',
            'website' => 'nullable|url|max:255',
            'phone' => 'nullable|string|max:30',
            'address' => 'nullable|string|max:500',
            'timezone' => 'nullable|string|max:50',
            'currency' => 'nullable|string|max:10',
        ]);

        $org->update($data);

        ActivityLog::log('updated', "Organization settings updated by {$user->name}", 'Organization', $org->id);

        return back()->with('success', 'Organization settings updated successfully!');
    }

    public function notifications()
    {
        $user = Auth::user();
        $notifications = NotificationLog::where('user_id', $user->id)->latest()->limit(30)->get();
        return response()->json($notifications);
    }

    public function markNotificationsRead()
    {
        $user = Auth::user();
        NotificationLog::where('user_id', $user->id)->where('is_read', false)->update(['is_read' => true]);
        return response()->json(['success' => true]);
    }

    public function activityLog()
    {
        $user = Auth::user();
        $query = ActivityLog::with('user');

        if (!$user->isSuperAdmin()) {
            $query->where('organization_id', $user->organization_id);
        }

        $logs = $query->latest()->paginate(50);
        return view('settings.activity-log', compact('logs'));
    }

    // Email Templates
    public function storeEmailTemplate(Request $request)
    {
        $user = Auth::user();
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'subject' => 'required|string|max:255',
            'body' => 'required|string',
            'category' => 'required|in:general,follow_up,proposal,welcome,reminder',
        ]);

        $org = $user->isSuperAdmin() ? Organization::first() : $user->organization;

        EmailTemplate::create(array_merge($data, ['organization_id' => $org->id]));
        ActivityLog::log('created', "Email template '{$data['name']}' created", 'EmailTemplate');

        return back()->with('success', 'Email template saved!');
    }

    public function destroyEmailTemplate($id)
    {
        $user = Auth::user();
        $template = EmailTemplate::findOrFail($id);

        if (!$user->isSuperAdmin() && $template->organization_id !== $user->organization_id) {
            abort(403);
        }

        $template->delete();
        return back()->with('success', 'Template deleted.');
    }
}

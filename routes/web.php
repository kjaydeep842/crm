<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LeadController;
use App\Http\Controllers\InquiryController;
use App\Http\Controllers\MeetingController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\DocumentController;
use App\Http\Controllers\AIAgentController;
use App\Http\Controllers\EmployeeProductivityController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\WhatsAppWebhookController;
use App\Http\Controllers\WhatsappController;

// Guest Auth Routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');
    
    // Google OAuth Routes
    Route::get('/auth/google', [\App\Http\Controllers\GoogleAuthController::class, 'redirect'])->name('auth.google');
    Route::get('/auth/google/callback', [\App\Http\Controllers\GoogleAuthController::class, 'callback'])->name('auth.google.callback');
});

// Quick Sandbox Access (accessible without standard guest check for developer convenience)
Route::get('/quick-login/{id}', [AuthController::class, 'quickLogin'])->name('quick-login');

// Public Pages & Inquiries
Route::get('/', [\App\Http\Controllers\LandingController::class, 'index'])->name('landing');
Route::post('/inquire', [\App\Http\Controllers\LandingController::class, 'submitInquiry'])->name('public.inquire');

Route::get('/features', function() { return view('public.features'); })->name('public.features');
Route::get('/how-it-works', function() { return view('public.how-it-works'); })->name('public.how-it-works');
Route::get('/pricing', function() { return view('public.pricing'); })->name('public.pricing');
Route::get('/about', function() { return view('public.about'); })->name('public.about');
Route::get('/contact', function() { return view('public.contact'); })->name('public.contact');
Route::get('/faq', function() { return view('public.faq'); })->name('public.faq');
Route::get('/integrations', function() { return view('public.integrations'); })->name('public.integrations');
Route::get('/security', function() { return view('public.security'); })->name('public.security');

// Protected Auth Routes
Route::middleware('auth')->group(function () {

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/switch-user/{id}', [DashboardController::class, 'switchUser'])->name('switch-user');
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // Notifications (API)
    Route::get('/notifications', [\App\Http\Controllers\OrganizationSettingsController::class, 'notifications'])->name('notifications.index');
    Route::post('/notifications/read', [\App\Http\Controllers\OrganizationSettingsController::class, 'markNotificationsRead'])->name('notifications.read');

    // Activity Log
    Route::get('/activity-log', [\App\Http\Controllers\OrganizationSettingsController::class, 'activityLog'])->name('activity.log');

    // Organization Settings
    Route::get('/settings/organization', [\App\Http\Controllers\OrganizationSettingsController::class, 'index'])->name('settings.organization');
    Route::post('/settings/organization', [\App\Http\Controllers\OrganizationSettingsController::class, 'update'])->name('settings.organization.update');
    Route::post('/settings/email-templates', [\App\Http\Controllers\OrganizationSettingsController::class, 'storeEmailTemplate'])->name('settings.email-templates.store');
    Route::delete('/settings/email-templates/{id}', [\App\Http\Controllers\OrganizationSettingsController::class, 'destroyEmailTemplate'])->name('settings.email-templates.destroy');

    // Razorpay Payments
    Route::prefix('billing')->group(function () {
        Route::get('/history', [\App\Http\Controllers\PaymentController::class, 'history'])->name('billing.history');
        Route::get('/invoice/{id}', [\App\Http\Controllers\PaymentController::class, 'downloadInvoice'])->name('billing.invoice');
        Route::post('/order/one-time', [\App\Http\Controllers\PaymentController::class, 'createOneTimeOrder'])->name('billing.order.one-time');
        Route::post('/verify/one-time', [\App\Http\Controllers\PaymentController::class, 'verifyOneTime'])->name('billing.verify.one-time');
        Route::post('/subscription/create', [\App\Http\Controllers\PaymentController::class, 'createSubscription'])->name('billing.subscription.create');
        Route::post('/subscription/verify', [\App\Http\Controllers\PaymentController::class, 'verifySubscription'])->name('billing.subscription.verify');
    });

    // Users & Organizations Management (Multitenancy)
    Route::prefix('users')->group(function () {
        Route::get('/', [UserController::class, 'index'])->name('users.index');
        Route::post('/', [UserController::class, 'store'])->name('users.store');
        Route::put('/{id}', [UserController::class, 'update'])->name('users.update');
        Route::delete('/{id}', [UserController::class, 'destroy'])->name('users.destroy');
    });
    Route::post('/organizations', [UserController::class, 'storeOrg'])->name('organizations.store');

    // Lead Management
    Route::prefix('leads')->group(function () {
        Route::get('/', [LeadController::class, 'index'])->name('leads.index');
        Route::post('/', [LeadController::class, 'store'])->name('leads.store');
        Route::get('/{id}', [LeadController::class, 'show'])->name('leads.show');
        Route::put('/{id}', [LeadController::class, 'update'])->name('leads.update');
        Route::delete('/{id}', [LeadController::class, 'destroy'])->name('leads.destroy');
        Route::post('/{id}/trigger-ai', [LeadController::class, 'triggerAI'])->name('leads.trigger-ai');
        Route::post('/{id}/followup', [LeadController::class, 'generateFollowUp'])->name('leads.followup');
        Route::post('/{id}/send-email', [LeadController::class, 'sendEmail'])->name('leads.send-email');
        Route::post('/{id}/send-whatsapp', [LeadController::class, 'sendWhatsApp'])->name('leads.send-whatsapp');
    });

    // Inquiry Management
    Route::prefix('inquiries')->group(function () {
        Route::get('/', [InquiryController::class, 'index'])->name('inquiries.index');
        Route::post('/', [InquiryController::class, 'store'])->name('inquiries.store');
        Route::post('/{id}/analyze', [InquiryController::class, 'analyze'])->name('inquiries.analyze');
        Route::post('/{id}/convert', [InquiryController::class, 'convertToLead'])->name('inquiries.convert');
    });

    // Meeting Management
    Route::prefix('meetings')->group(function () {
        Route::get('/', [MeetingController::class, 'index'])->name('meetings.index');
        Route::post('/', [MeetingController::class, 'store'])->name('meetings.store');
        Route::get('/{id}', [MeetingController::class, 'show'])->name('meetings.show');
        Route::post('/{id}/prep-ai', [MeetingController::class, 'prepAI'])->name('meetings.prep-ai');
        Route::post('/{id}/notes-ai', [MeetingController::class, 'processNotesAI'])->name('meetings.notes-ai');
    });

    // Task Management
    Route::prefix('tasks')->group(function () {
        Route::get('/', [TaskController::class, 'index'])->name('tasks.index');
        Route::post('/', [TaskController::class, 'store'])->name('tasks.store');
        Route::post('/{id}/toggle', [TaskController::class, 'toggle'])->name('tasks.toggle');
        Route::post('/generate-priority', [TaskController::class, 'generatePriorityTasks'])->name('tasks.generate-priority');
    });

    // Document Generator
    Route::prefix('documents')->group(function () {
        Route::get('/', [DocumentController::class, 'index'])->name('documents.index');
        Route::post('/', [DocumentController::class, 'store'])->name('documents.store');
        Route::get('/{id}', [DocumentController::class, 'show'])->name('documents.show');
    });

    // WhatsApp & Email AI Agents
    Route::prefix('agents')->group(function () {
        Route::get('/', [AIAgentController::class, 'index'])->name('agents.index');
        Route::post('/simulate-whatsapp', [AIAgentController::class, 'simulateWhatsApp'])->name('agents.simulate-whatsapp');
        Route::post('/simulate-email', [AIAgentController::class, 'simulateEmail'])->name('agents.simulate-email');
    });

    // Employee Productivity
    Route::prefix('productivity')->group(function () {
        Route::get('/', [EmployeeProductivityController::class, 'index'])->name('productivity.index');
        Route::post('/refresh-report', [EmployeeProductivityController::class, 'generateNewReport'])->name('productivity.refresh-report');
    });

    // WhatsApp Shared Inbox
    Route::prefix('whatsapp')->group(function () {
        Route::get('/', [WhatsappController::class, 'inbox'])->name('whatsapp.inbox');
        Route::post('/', [WhatsappController::class, 'store'])->name('whatsapp.store');
        Route::get('/{conversation}', [WhatsappController::class, 'show'])->name('whatsapp.show');
        Route::post('/{conversation}/reply', [WhatsappController::class, 'reply'])->name('whatsapp.reply');
    });

    // Reports & Forecasts
    Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');

});

// Live Webhook Endpoints (exempt from CSRF/auth for external services)
Route::get('/webhook/whatsapp', [WhatsAppWebhookController::class, 'verify']);
Route::post('/webhook/whatsapp', [WhatsAppWebhookController::class, 'handle']);
Route::post('/webhook/razorpay', [\App\Http\Controllers\PaymentController::class, 'webhook'])->name('webhook.razorpay');

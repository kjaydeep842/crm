<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('inquiries', function (Blueprint $table) {
            $table->id();
            $table->string('customer_name');
            $table->string('contact');
            $table->text('message');
            $table->string('source')->default('Manual'); // Website, WhatsApp, Facebook, Instagram, Email, Manual
            $table->timestamp('date');
            
            // AI Analysis
            $table->text('ai_summary')->nullable();
            $table->string('ai_intent')->nullable();
            $table->string('ai_urgency')->nullable();
            $table->string('ai_budget_estimate')->nullable();
            $table->string('ai_recommended_department')->nullable();
            
            $table->foreignId('assigned_to')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('lead_id')->nullable()->constrained('leads')->onDelete('set null');
            $table->string('status')->default('Pending'); // Pending, Processed
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('inquiries');
    }
};

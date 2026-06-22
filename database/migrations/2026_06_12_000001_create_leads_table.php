<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('leads', function (Blueprint $table) {
            $table->id();
            $table->string('full_name');
            $table->string('company_name')->nullable();
            $table->string('mobile')->nullable();
            $table->string('email')->nullable();
            $table->string('website')->nullable();
            $table->string('industry')->nullable();
            $table->string('lead_source')->default('Manual'); // Website, WhatsApp, Facebook, Instagram, Email, Manual
            $table->decimal('budget', 15, 2)->nullable();
            $table->text('requirement')->nullable();
            $table->text('notes')->nullable();
            $table->string('status')->default('New'); // New, Contacted, Qualified, Proposal Sent, Negotiation, Won, Lost
            
            // AI Analysis fields
            $table->integer('ai_score')->default(0); // 0-100
            $table->string('ai_qualification')->nullable(); // Hot, Warm, Cold
            $table->string('ai_priority')->nullable(); // High, Medium, Low
            $table->text('ai_recommended_followup')->nullable();
            
            // AI Assistant fields
            $table->text('ai_summary')->nullable();
            $table->string('ai_intent')->nullable();
            $table->string('ai_urgency')->nullable();
            $table->string('ai_budget_estimate')->nullable();
            $table->string('ai_recommended_department')->nullable();
            $table->integer('ai_sales_probability')->nullable(); // 0-100
            $table->string('ai_recommended_service')->nullable();

            $table->foreignId('assigned_to')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('leads');
    }
};

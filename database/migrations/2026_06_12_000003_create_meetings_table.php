<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('meetings', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('customer_name')->nullable();
            $table->foreignId('lead_id')->nullable()->constrained('leads')->onDelete('cascade');
            $table->date('date');
            $table->time('time');
            $table->string('meeting_link')->nullable();
            $table->string('location')->nullable();
            $table->text('notes')->nullable();
            
            // Meeting prep AI
            $table->text('ai_customer_summary')->nullable();
            $table->text('ai_previous_interactions')->nullable();
            $table->text('ai_suggested_topics')->nullable();

            // Meeting post notes AI
            $table->text('transcript')->nullable();
            $table->text('ai_summary')->nullable();
            $table->text('ai_action_items')->nullable();
            $table->text('ai_followup_tasks')->nullable();
            $table->text('ai_next_meeting_suggestions')->nullable();

            $table->string('status')->default('Scheduled'); // Scheduled, Completed, Cancelled
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('meetings');
    }
};

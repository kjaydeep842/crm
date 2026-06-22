<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tasks', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('type'); // Call Customer, Send Proposal, Follow-up, Demo Meeting, Payment Reminder
            $table->foreignId('lead_id')->nullable()->constrained('leads')->onDelete('cascade');
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('cascade'); // Assigned agent
            $table->date('due_date');
            $table->string('priority')->default('Medium'); // High, Medium, Low
            $table->string('status')->default('Pending'); // Pending, Completed
            $table->text('notes')->nullable();
            $table->boolean('ai_suggested')->default(false); // Whether created by AI Priority Tasks
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tasks');
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Notifications table
        if (!Schema::hasTable('notifications_log')) {
            Schema::create('notifications_log', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('user_id');
                $table->string('type'); // lead_added, task_due, meeting_reminder, etc.
                $table->string('title');
                $table->text('body')->nullable();
                $table->string('link')->nullable();
                $table->boolean('is_read')->default(false);
                $table->timestamps();
                $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            });
        }

        // Activity / Audit Log
        if (!Schema::hasTable('activity_logs')) {
            Schema::create('activity_logs', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('user_id')->nullable();
                $table->unsignedBigInteger('organization_id')->nullable();
                $table->string('entity_type')->nullable(); // Lead, Meeting, etc.
                $table->unsignedBigInteger('entity_id')->nullable();
                $table->string('action'); // created, updated, deleted, ai_triggered
                $table->text('description')->nullable();
                $table->json('changes')->nullable();
                $table->string('ip_address')->nullable();
                $table->timestamps();
            });
        }

        // Pipeline Stages (Kanban)
        if (!Schema::hasTable('pipeline_stages')) {
            Schema::create('pipeline_stages', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('organization_id');
                $table->string('name'); // New, Contacted, Negotiation, Won, Lost
                $table->string('color')->default('#6366f1');
                $table->integer('position')->default(0);
                $table->timestamps();
            });
        }

        // Tags for leads
        if (!Schema::hasTable('tags')) {
            Schema::create('tags', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('organization_id');
                $table->string('name');
                $table->string('color')->default('#6366f1');
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('lead_tag')) {
            Schema::create('lead_tag', function (Blueprint $table) {
                $table->unsignedBigInteger('lead_id');
                $table->unsignedBigInteger('tag_id');
                $table->primary(['lead_id', 'tag_id']);
            });
        }

        // Email Templates
        if (!Schema::hasTable('email_templates')) {
            Schema::create('email_templates', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('organization_id');
                $table->string('name');
                $table->string('subject');
                $table->longText('body');
                $table->string('category')->default('general'); // follow_up, proposal, welcome
                $table->timestamps();
            });
        }

        // Organization Settings
        if (!Schema::hasColumns('organizations', ['website', 'phone', 'address', 'logo', 'timezone', 'currency', 'trial_ends_at'])) {
            Schema::table('organizations', function (Blueprint $table) {
                $table->string('website')->nullable()->after('name');
                $table->string('phone')->nullable()->after('website');
                $table->text('address')->nullable()->after('phone');
                $table->text('logo')->nullable()->after('address');
                $table->string('timezone')->default('Asia/Kolkata')->after('logo');
                $table->string('currency')->default('INR')->after('timezone');
                $table->timestamp('trial_ends_at')->nullable()->after('currency');
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('lead_tag');
        Schema::dropIfExists('tags');
        Schema::dropIfExists('pipeline_stages');
        Schema::dropIfExists('activity_logs');
        Schema::dropIfExists('notifications_log');
        Schema::dropIfExists('email_templates');
        Schema::table('organizations', function (Blueprint $table) {
            $table->dropColumn(['website', 'phone', 'address', 'logo', 'timezone', 'currency', 'trial_ends_at']);
        });
    }
};

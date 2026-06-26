<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('whatsapp_messages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('whatsapp_conversation_id')->constrained()->onDelete('cascade');
            $table->enum('sender_type', ['user', 'lead', 'ai', 'system'])->default('lead');
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null');
            $table->text('message_body');
            $table->string('message_id')->nullable()->unique();
            $table->boolean('is_read')->default(false);
            $table->string('status')->default('sent'); // sent, delivered, read, failed
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('whatsapp_messages');
    }
};

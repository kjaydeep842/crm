<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('whatsapp_templates', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('language')->default('en_US');
            $table->string('category')->default('MARKETING');
            $table->text('body');
            $table->json('variables')->nullable();
            $table->string('status')->default('APPROVED'); // APPROVED, PENDING, REJECTED
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('whatsapp_templates');
    }
};

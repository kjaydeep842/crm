<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('documents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('lead_id')->constrained('leads')->onDelete('cascade');
            $table->string('type'); // Proposal, Quotation, Invoice
            $table->string('document_number'); // e.g. PROP-001, INV-001
            $table->string('title')->nullable();
            $table->json('content'); // Contains sections, items, prices, terms
            $table->decimal('amount', 15, 2)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('documents');
    }
};

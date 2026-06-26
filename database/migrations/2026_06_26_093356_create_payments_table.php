<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('organization_id');
            $table->unsignedBigInteger('user_id');

            // Payment type
            $table->enum('type', ['one_time', 'subscription'])->default('one_time');

            // Plan info
            $table->string('plan'); // starter, professional, business, enterprise
            $table->decimal('amount', 10, 2);
            $table->string('currency')->default('INR');

            // Razorpay identifiers
            $table->string('razorpay_order_id')->nullable();
            $table->string('razorpay_payment_id')->nullable();
            $table->string('razorpay_signature')->nullable();
            $table->string('razorpay_subscription_id')->nullable();

            // Status
            $table->enum('status', ['pending', 'success', 'failed', 'refunded'])->default('pending');
            $table->timestamp('paid_at')->nullable();

            // Subscription billing
            $table->timestamp('current_period_end')->nullable(); // when subscription renews/ends

            $table->text('notes')->nullable();
            $table->timestamps();

            $table->foreign('organization_id')->references('id')->on('organizations')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });

        // Add subscription_id to organizations
        Schema::table('organizations', function (Blueprint $table) {
            $table->string('razorpay_subscription_id')->nullable()->after('trial_ends_at');
            $table->timestamp('subscription_ends_at')->nullable()->after('razorpay_subscription_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payments');
        Schema::table('organizations', function (Blueprint $table) {
            $table->dropColumn(['razorpay_subscription_id', 'subscription_ends_at']);
        });
    }
};

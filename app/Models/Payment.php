<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $fillable = [
        'organization_id', 'user_id', 'type', 'plan', 'amount', 'currency',
        'razorpay_order_id', 'razorpay_payment_id', 'razorpay_signature',
        'razorpay_subscription_id', 'status', 'paid_at', 'current_period_end', 'notes',
    ];

    protected $casts = [
        'paid_at' => 'datetime',
        'current_period_end' => 'datetime',
    ];

    public function organization()
    {
        return $this->belongsTo(Organization::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

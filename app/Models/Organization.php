<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Organization extends Model
{
    protected $fillable = ['name', 'package', 'ai_credits_used'];

    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    public function getAiCreditLimitAttribute()
    {
        return match($this->package) {
            'starter' => 1000,
            'professional' => 10000,
            'business' => 50000,
            'enterprise' => 999999999, // unlimited
            default => 0,
        };
    }

    public function getMaxUsersAttribute()
    {
        return match($this->package) {
            'starter' => 5,
            'professional' => 15,
            'business' => 50,
            'enterprise' => 999999999, // unlimited
            default => 0,
        };
    }

    public function hasAiCredits($amount = 1): bool
    {
        return ($this->ai_credits_used + $amount) <= $this->ai_credit_limit;
    }

    public function useAiCredits($amount = 1): void
    {
        $this->increment('ai_credits_used', $amount);
    }

    public function canAddUser(): bool
    {
        return $this->users()->count() < $this->max_users;
    }
}

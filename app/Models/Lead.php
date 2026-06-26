<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lead extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function assignedAgent()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function inquiries()
    {
        return $this->hasMany(Inquiry::class);
    }

    public function meetings()
    {
        return $this->hasMany(Meeting::class);
    }

    public function tasks()
    {
        return $this->hasMany(Task::class);
    }

    public function documents()
    {
        return $this->hasMany(Document::class);
    }

    public function activities()
    {
        return $this->hasMany(Activity::class)->latest();
    }

    public function whatsappConversations()
    {
        return $this->hasMany(WhatsappConversation::class);
    }
}

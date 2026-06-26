<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WhatsappConversation extends Model
{
    use HasFactory;

    protected $fillable = ['lead_id', 'phone_number', 'status', 'last_message_at'];

    public function lead()
    {
        return $this->belongsTo(Lead::class);
    }

    public function messages()
    {
        return $this->hasMany(WhatsappMessage::class);
    }
}

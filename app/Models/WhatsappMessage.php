<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WhatsappMessage extends Model
{
    use HasFactory;

    protected $fillable = [
        'whatsapp_conversation_id', 'sender_type', 'user_id', 
        'message_body', 'message_id', 'is_read', 'status'
    ];

    public function conversation()
    {
        return $this->belongsTo(WhatsappConversation::class, 'whatsapp_conversation_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

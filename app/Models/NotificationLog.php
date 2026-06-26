<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NotificationLog extends Model
{
    protected $table = 'notifications_log';
    protected $fillable = ['user_id', 'type', 'title', 'body', 'link', 'is_read'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public static function send(int $userId, string $type, string $title, string $body = '', string $link = '')
    {
        return static::create([
            'user_id' => $userId,
            'type' => $type,
            'title' => $title,
            'body' => $body,
            'link' => $link,
        ]);
    }

    public static function sendToOrg(int $orgId, string $type, string $title, string $body = '', string $link = '')
    {
        $users = User::where('organization_id', $orgId)->pluck('id');
        foreach ($users as $userId) {
            static::send($userId, $type, $title, $body, $link);
        }
    }
}

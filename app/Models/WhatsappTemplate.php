<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WhatsappTemplate extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'language', 'category', 'body', 'variables', 'status'];

    protected $casts = [
        'variables' => 'array',
    ];
}

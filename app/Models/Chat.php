<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Chat extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'first_name',
        'username',
        'type',
        'small_chat_photo',
        'last_message',
        'last_message_text',
        'last_message_from',
        'last_message_user_id',
        'last_message_user_name',
        'is_unread',
    ];

    protected $casts = [
        'is_unread' => 'boolean',
        'last_message' => 'datetime',
    ];

    public function messages(): HasMany
    {
        return $this->hasMany(Message::class);
    }

    public function unreadMessages()
    {
        return $this->hasMany(Message::class)->where('is_unread', true);
    }
}

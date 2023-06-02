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
		'is_unread',
	];

	protected $casts = [
		'is_unread' => 'boolean',
	];

	public function messages(): HasMany
	{
		return $this->hasMany(Message::class);
	}
}

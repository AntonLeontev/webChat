<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Message extends Model
{
    use HasFactory;

	protected $fillable = [
		'id',
		'chat_id',
		'message_id',
		'text',
		'from',
		'photo',
		'photo_height',
		'photo_width',
		'document',
		'document_name',
	];

	public function chat(): BelongsTo
	{
		return $this->belongsTo(Chat::class);
	}
}

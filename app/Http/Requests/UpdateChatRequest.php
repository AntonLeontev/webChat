<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateChatRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'first_name' => ['sometimes', 'string', 'max:255'],
			'username' => ['sometimes', 'string', 'max:255'],
			'type' => ['sometimes', 'string', 'max:255'],
			'small_chat_photo' => ['sometimes', 'string', 'max:255'],
			'last_message' => ['sometimes', 'date'],
			'is_unread' => ['sometimes', 'boolean'],
        ];
    }
}

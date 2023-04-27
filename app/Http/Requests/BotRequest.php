<?php

namespace App\Http\Requests;

use App\Models\Bot;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class BotRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'bot.telegram_id' => [
                'required',
                'integer',
                Rule::unique(Bot::class, 'telegram_id')->ignore($this->bot["id"])
            ],
            'bot.user_id' => ['required', 'integer', 'exists:users,id'],
            'bot.username' => [
                'required',
                'string',
                'between:5,32',
                'regex:/^[a-zA-Z0-9_]+$/',
                Rule::unique(Bot::class, 'username')->ignore($this->bot["id"])
            ],
            'bot.name' => ['required', 'string', 'between:1,64'],
            'bot.about' => ['nullable', 'string', 'max:120'],
            'bot.description' => ['nullable', 'string', 'max:512'],
            'bot.avatar' => ['nullable', 'url', 'max:2048'],
            'bot.token' => [
                'required',
                'string',
                'max:255',
                Rule::unique(Bot::class, 'token')->ignore($this->bot["id"])
            ],
            'bot.is_active' => ['required', 'boolean'],
            'bot.welcome_message_text' => ['nullable', 'string', 'max:4096'],
            'bot.help_message_text' => ['nullable', 'string', 'max:4096'],
            'bot.subreddits' => ['nullable'],
        ];
    }
}

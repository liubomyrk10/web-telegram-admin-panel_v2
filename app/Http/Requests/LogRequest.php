<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class LogRequest extends FormRequest
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
            'log.bot_id' => 'required|integer|exists:bots,id',
            'log.subscriber_id' => 'required|integer|exists:subscribers,id',
            'log.chat_type' => [
                'required',
                Rule::in(['private', 'group', 'supergroup', 'channel'])
            ],
            'log.command' => 'nullable|string|max:32',
            'log.message' => 'nullable|string|max:4096',
            'log.send_time_taken' => 'required|integer|min:0'
        ];
    }
}

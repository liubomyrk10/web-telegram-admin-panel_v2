<?php

namespace App\Http\Requests;

use App\Models\Subscriber;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SubscriberRequest extends FormRequest
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
            'subscriber.bot_id' => 'required|exists:bots,id',
            'subscriber.telegram_id' => [
                'required',
                'integer',
                Rule::unique(Subscriber::class, 'telegram_id')->ignore($this->subscriber["id"])
            ],
            'subscriber.first_name' => 'required|string|max:64',
            'subscriber.last_name' => 'nullable|string|max:64',
            'subscriber.username' => 'nullable|string|max:32',
            'subscriber.lang' => 'nullable|string',
            'subscriber.is_blocked' => 'nullable|boolean'
        ];
    }
}

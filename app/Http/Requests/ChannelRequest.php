<?php

namespace App\Http\Requests;

use App\Models\Channel;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ChannelRequest extends FormRequest
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
            'channel.telegram_id' => [
                'required',
                'integer',
                Rule::unique(Channel::class, 'telegram_id')->ignore($this->channel["id"])
            ],
            'channel.bot_id' => ['required', 'integer'],
            'channel.title' => ['nullable', 'string', 'max:255'],
            'channel.username' => ['nullable', 'string', 'max:32'],
            'channel.description' => ['nullable', 'string', 'max:255'],
            'channel.photo' => ['nullable', 'string', 'max:2048'],
            'channel.member_count' => ['nullable', 'integer'],
            'channel.is_public' => ['nullable', 'boolean'],
            'channel.invite_link' => ['nullable', 'string', 'max:2048'],
        ];
    }
}

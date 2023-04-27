<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UserRequest extends FormRequest
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
            'user.name' => ['required', 'string', 'max:255'],
            'user.email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique(User::class, 'email')->ignore($this->user["id"])
            ],
            'user.password' => ['nullable', 'min:8'],
            'user.telegram_id' => [
                'required',
                'integer',
                Rule::unique(User::class, 'telegram_id')->ignore($this->user["id"])
            ],
            'user.first_name' => ['required', 'string', 'max:64'],
            'user.last_name' => ['nullable', 'string', 'max:64'],
            'user.username' => ['nullable', 'string', 'max:32'],
            'user.photo_url' => ['nullable', 'string', 'max:2048'],
        ];
    }
}

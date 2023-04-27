<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PostScheduleRequest extends FormRequest
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
            'post_schedule.channel_id' => 'required|exists:channels,id',
            'post_schedule.post_id' => 'required|exists:posts,id',
            'post_schedule.post_time' => 'required|date_format:Y-m-d H:i:s',
        ];
    }
}

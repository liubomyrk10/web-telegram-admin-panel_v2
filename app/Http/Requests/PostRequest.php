<?php

namespace App\Http\Requests;

use App\Models\Post;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class PostRequest extends FormRequest
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
            'post.reddit_id' => [
                'required',
                'max:7',
                Rule::unique(Post::class, 'reddit_id')->ignore($this->post["id"])
            ],
            'post.subreddit_id' => 'required|exists:subreddits,id',
            'post.type' => [
                'required',
                Rule::in(['text', 'image', 'video', 'youtube', 'gif'])
            ],
            'post.title' => 'nullable|max:300',
            'post.description' => 'nullable|max:4096',
            'post.url' => 'nullable|max:2048',
            'post.permalink' => 'max:2048',
            'post.width' => 'nullable|integer|min:0',
            'post.height' => 'nullable|integer|min:0',
            'post.is_nsfw' => 'boolean',
            'post.is_spoiler' => 'boolean',
            'post.bots' => 'nullable',
        ];
    }
}

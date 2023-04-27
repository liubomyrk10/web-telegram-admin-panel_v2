<?php

namespace App\Orchid\Screens\PostSchedule;

use App\Http\Requests\PostScheduleRequest;
use App\Models\Post;
use App\Models\PostSchedule;
use App\Orchid\Layouts\PostsSchedule\PostSchedulePreviewListener;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Screen;
use Orchid\Support\Facades\Toast;

class PostScheduleEditScreen extends Screen
{
    public PostSchedule $post_schedule;

    public function query(PostSchedule $postSchedule): iterable
    {
        return [
            'post_schedule' => $postSchedule
        ];
    }

    public function name(): ?string
    {
        return $this->post_schedule->exists ? 'Редагувати відкладений пост' : 'Додати відкладений пост';
    }

    public function description(): ?string
    {
        return "Інформація про відкладений пост.";
    }

    public function permission(): ?iterable
    {
        return [
            'platform.posts_schedule',
        ];
    }

    public function commandBar(): iterable
    {
        return [
            Button::make(__('Remove'))
                ->icon('trash')
                ->confirm(
                    'Ви впевнені, що бажаєте видалити відкладений пост?'
                )
                ->method('remove')
                ->canSee($this->post_schedule->exists),

            Button::make(__('Save'))
                ->icon('check')
                ->method('save'),
        ];
    }

    public function layout(): iterable
    {
        return [
            PostSchedulePreviewListener::class
        ];
    }

    public function save(PostSchedule $postSchedule, PostScheduleRequest $request)
    {
        $validatedPostSchedule = $request->validated()['post_schedule'];
        $postSchedule->channel_id = $validatedPostSchedule['channel_id'];
        $postSchedule->post_id = $validatedPostSchedule['post_id'];
        $postSchedule->post_time = $validatedPostSchedule['post_time'];

        $postSchedule->save();

        Toast::info('Відкладений пост успішно збережено!');

        return redirect()->route('platform.posts_schedule');
    }

    public function remove(PostSchedule $postSchedule)
    {
        $postSchedule->delete();

        Toast::info('Відкладений пост було успішно видалено!');

        return redirect()->route('platform.posts_schedule');
    }

    public function asyncPostPreview(array $request)
    {
        ['post_id' => $postId] = $request;
        $post = Post::find($postId);
        return [
            'post_schedule.post_id' => $post->id,
            'post.reddit_id' => $post->reddit_id,
            'post.subreddit_id' => $post->subreddit->id,
            'post.type' => $post->type,
            'post.title' => $post->title,
            'post.description' => $post->description,
            'post.url' => $post->url,
            'post.permalink' => $post->permalink,
            'post.width' => $post->width,
            'post.height' => $post->height,
            'post.is_spoiler' => $post->is_spoiler,
            'post.is_nsfw' => $post->is_nsfw,
        ];
    }
}

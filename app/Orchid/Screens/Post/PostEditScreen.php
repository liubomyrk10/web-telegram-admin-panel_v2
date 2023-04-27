<?php

namespace App\Orchid\Screens\Post;

use App\Http\Requests\PostRequest;
use App\Models\Post;
use App\Models\Subreddit;
use App\Orchid\Layouts\Post\PostListener;
use App\Services\RedditAPI\RedditPostDto;
use App\Services\RedditAPI\RedditPostService;
use Illuminate\Support\Arr;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Screen;
use Orchid\Support\Facades\Toast;

class PostEditScreen extends Screen
{

    public Post $post;

    public function query(Post $post): iterable
    {
        return [
            'post' => $post
        ];
    }

    public function name(): ?string
    {
        return $this->post->exists ? 'Редагувати пост' : 'Додати пост';
    }

    public function description(): ?string
    {
        return "Інформація про пост. Присутня підтримка генерації із Reddit.";
    }

    /**
     * @return iterable|null
     */
    public function permission(): ?iterable
    {
        return [
            'platform.posts',
        ];
    }

    /**
     * The screen's action buttons.
     *
     * @return \Orchid\Screen\Action[]
     */
    public function commandBar(): iterable
    {
        return [
            Button::make(__('Remove'))
                ->icon('trash')
                ->confirm(
                    'Ви впевнені, що бажаєте видалити пост?'
                )
                ->method('remove')
                ->canSee($this->post->exists),

            Button::make(__('Save'))
                ->icon('check')
                ->method('save'),
        ];
    }

    /**
     * The screen's layout elements.
     *
     * @return \Orchid\Screen\Layout[]|string[]
     */
    public function layout(): iterable
    {
        return [
            /*            Layout::block(PostEditLayout::class)
                            ->title('Інформація про контент поста')
                            ->description('Оновіть інформацію про вміст поста.')
                            ->commands(
                                Button::make(__('Save'))
                                    ->type(Color::DEFAULT())
                                    ->icon('check')
                                    ->canSee($this->post->exists)
                                    ->method('save')
                            ),
                        Layout::block(PostSecondaryEditLayout::class)
                            ->title('Додаткова інформація поста')
                            ->description('Оновіть інформацію про другорядні дані.')
                            ->commands(
                                Button::make(__('Save'))
                                    ->type(Color::DEFAULT())
                                    ->icon('check')
                                    ->canSee($this->post->exists)
                                    ->method('save')
                            ),*/

            PostListener::class
        ];
    }

    public function save(Post $post, PostRequest $request)
    {
        $validatedPost = $request->validated()['post'];
        $post->reddit_id = $validatedPost['reddit_id'];
        $post->subreddit_id = $validatedPost['subreddit_id'];
        $post->type = $validatedPost['type'];
        $post->title = $validatedPost['title'];
        $post->description = $validatedPost['description'];
        $post->url = $validatedPost['url'];
        $post->permalink = $validatedPost['permalink'];
        $post->width = $validatedPost['width'];
        $post->height = $validatedPost['height'];
        $post->is_nsfw = $validatedPost['is_nsfw'];
        $post->is_spoiler = $validatedPost['is_spoiler'];

        $post->save();
        $post->bots()->sync($validatedPost['bots']);

        Toast::info('Пост успішно збережено!');

        return redirect()->route('platform.posts');
    }

    public function remove(Post $post)
    {
        $post->delete();

        Toast::info('Пост було успішно видалено!');

        return redirect()->route('platform.posts');
    }

    public function asyncGenerate(array $request)
    {
        ['subreddit_id' => $subredditId, 'type' => $type, 'parse_type' => $parseType] = $request;
        $filteredTopPosts = RedditPostService::getFilteredPosts(
            (int)$subredditId,
            $type,
            $parseType,
            100
        );

        if (!Arr::whereNotNull($filteredTopPosts)) {
            return [
                'post.subreddit_id' => $subredditId,
                'post.type' => $type,
                'post.parse_type' => $parseType,
                'post.generate_status' => "Не вдалось знайти пост типу {$type} по " .
                    Subreddit::find($subredditId)->name
            ];
        }

        /** @var RedditPostDto $generatedPost */
        $newPosts = collect($filteredTopPosts)->filter(function ($post) {
            return !Post::withTrashed()->where('reddit_id', $post->id)->first();
        });

        $newPost = null;
        if ($newPosts->count()) {
            $newPost = $newPosts->random();
        }

        if (is_null($newPost)) {
            return [
                'post.subreddit_id' => $subredditId,
                'post.type' => $type,
                'post.parse_type' => $parseType,
                'post.generate_status' => "Пост/пости з таким reddit id вже присутні в БД"
            ];
        } else {
            return [
                'post.reddit_id' => $newPost->id,
                'post.parse_type' => $parseType,
                'post.subreddit_id' => $subredditId,
                'post.type' => $type,
                'post.title' => $newPost->title,
                'post.description' => $newPost->description,
                'post.url' => $newPost->url,
                'post.permalink' => $newPost->permalink,
                'post.width' => $newPost->width,
                'post.height' => $newPost->height,
                'post.is_spoiler' => $newPost->spoiler,
                'post.is_nsfw' => $newPost->over18,
                'post.generate_status' => "Успіх! {$newPost->id} - " . Subreddit::find($subredditId)->name,
            ];
        }
    }
}

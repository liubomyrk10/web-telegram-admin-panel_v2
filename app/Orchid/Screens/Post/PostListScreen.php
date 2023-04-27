<?php

namespace App\Orchid\Screens\Post;

use App\Models\Bot;
use App\Models\Post;
use App\Models\Subreddit;
use App\Orchid\Layouts\Post\PostListLayout;
use App\Orchid\Layouts\Post\PostMassiveGenerateListener;
use App\Services\RedditAPI\RedditPostDto;
use App\Services\RedditAPI\RedditPostService;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Actions\ModalToggle;
use Orchid\Screen\Screen;
use Orchid\Support\Facades\Layout;
use Orchid\Support\Facades\Toast;

class PostListScreen extends Screen
{
    /**
     * Fetch data to be displayed on the screen.
     *
     * @return array
     */
    public function query(): iterable
    {
        $postBuilder = Post::with('bots')->whereHas('bots', function ($query) {
            $query->where('user_id', auth()->user()->id);
        });
        return [
            'posts' => $postBuilder->filters()->defaultSort('created_at', 'desc')->paginate()
        ];
    }

    /**
     * The name of the screen displayed in the header.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return 'Пости';
    }

    /**
     * Display header description.
     *
     * @return string|null
     */
    public function description(): ?string
    {
        return 'Список підготовлених публікацій адміністратора телеграм ботів, з вебресурсу reddit.';
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
            ModalToggle::make('Масове створення')->modal('massiveGenerateModal')
                ->method('massiveGenerate')->icon('plus-alt'),
            Link::make(__('Add'))
                ->icon('plus')
                ->route('platform.posts.create'),
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
            Layout::modal(
                'massiveGenerateModal',
                PostMassiveGenerateListener::class
            )->title('Масове створення постів')
                ->withoutApplyButton(),
            PostListLayout::class
        ];
    }

    /**
     * @param Request $request
     */
    public function remove(Request $request): void
    {
        Post::query()->findOrFail($request->get('id'))->delete();
        Toast::info('Пост видалено успішно!');
    }

    public function asyncMassiveGenerate(array $request)
    {
        [
            'subreddit_id' => $subredditId,
            'type' => $type,
            'parse_type' => $parseType,
            'bots' => $botIds,
            'can_insert' => $canInsert
        ] = $request;

        if (!Arr::whereNotNull($botIds)) {
            return [
                'post.subreddit_id' => $subredditId,
                'post.type' => $type,
                'post.parse_type' => $parseType,
                'post.generate_status' => "Ви забули обрати бота/ботів!"
            ];
        }

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

        $newPosts = collect($filteredTopPosts)->filter(function ($post) {
            return !Post::withTrashed()->where('reddit_id', $post->id)->first();
        });

        $postsCount = count($newPosts);
        if ($postsCount) {
            if ($canInsert) {
                $posts = [];

                /** @var RedditPostDto $redditPost */
                foreach ($newPosts as $redditPost) {
                    $post = [
                        'reddit_id' => $redditPost->id,
                        'subreddit_id' => $subredditId,
                        'type' => $type,
                        'title' => $redditPost->title,
                        'description' => $redditPost->description,
                        'url' => $redditPost->url,
                        'permalink' => $redditPost->permalink,
                        'width' => $redditPost->width,
                        'height' => $redditPost->height,
                        'is_nsfw' => $redditPost->over18,
                        'is_spoiler' => $redditPost->spoiler,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];

                    $posts[] = $post;
                }

                $insertedIds = [];
                foreach ($posts as $post) {
                    $insertedIds[] = Post::insertGetId($post);
                }

                $bots = Bot::whereIn('id', $botIds)->get();
                $posts = Post::whereIn('id', $insertedIds)->get();

                foreach ($posts as $post) {
                    $post->bots()->attach($bots);
                }

                return [
                    'post.subreddit_id' => $subredditId,
                    'post.type' => $type,
                    'post.parse_type' => $parseType,
                    'post.generate_status' => "Всього вдалось отримати {$postsCount} постів по субреддіту " .
                        Subreddit::find($subredditId)->name
                        . " ($type, $parseType). Всі вони успішно збережені"
                ];
            }

            return [
                'post.subreddit_id' => $subredditId,
                'post.type' => $type,
                'post.parse_type' => $parseType,
                'post.generate_status' => "Всього вдалось отримати {$postsCount} постів по субреддіту " .
                    Subreddit::find($subredditId)->name
                    . " ($type, $parseType). Після активації checkbox'а, та повторного вибору субреддіта - всі пости збережуться в БД."
            ];
        } else {
            return [
                'post.subreddit_id' => $subredditId,
                'post.type' => $type,
                'post.parse_type' => $parseType,
                'post.generate_status' => "Всі знайдені пости, вже присутні в БД."
            ];
        }
    }

    public function MassiveGenerate(Request $request)
    {
        $request = $request['post'];
        dd($request);
    }
}

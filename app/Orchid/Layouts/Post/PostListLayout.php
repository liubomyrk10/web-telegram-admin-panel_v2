<?php

namespace App\Orchid\Layouts\Post;

use App\Models\Post;
use Illuminate\Support\Str;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Actions\DropDown;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Fields\CheckBox;
use Orchid\Screen\Layouts\Table;
use Orchid\Screen\TD;

class PostListLayout extends Table
{
    /**
     * Data source.
     *
     * The name of the key to fetch it from the query.
     * The results of which will be elements of the table.
     *
     * @var string
     */
    protected $target = 'posts';

    /**
     * Get the table cells to be displayed.
     *
     * @return TD[]
     */
    protected function columns(): iterable
    {
        return [
            TD::make('reddit_id', 'Reddit ID')->defaultHidden(),

            TD::make('content_preview', 'Preview')
                ->width('100')
                ->render(function (Post $post) {
                    return match ($post->type) {
                        'image' => "<img src='{$post->url}' class='d-block img-fluid img-thumbnail'>",
                        'video' => "<div class='img-thumbnail d-flex justify-content-center'>VIDEO</div>",
                        'gif' => "<img src='{$post->url}' class=' d-block img-fluid img-thumbnail'>",
                        'text' => "<div class='img-thumbnail d-flex justify-content-center'>TEXT</div>",
                        'youtube' => "<div class='img-thumbnail d-flex justify-content-center'>YOUTUBE</div>",
                        default => ''
                    };
                }),

            TD::make('subreddit_id', 'Субреддіт')
                ->render(fn(Post $post) => $post->subreddit->name),

            TD::make('bots', 'Для ботів')->render(function (Post $post) {
                $subreddits = $post->bots();
                return $subreddits->pluck('username')->implode(', ');
            }),

            TD::make('type', 'Тип')->sort()->filter(),

            TD::make('title', 'Заголовок')
                ->sort()
                ->render(
                    fn(Post $post) => Str::limit($post->title, 20)
                )->filter(),

            TD::make('description', 'Опис')->render(
                fn(Post $post) => Str::limit($post->description, 50)
            )->defaultHidden(),

            TD::make('url', 'Контент')->render(function (Post $post) {
                return Link::make('go')
                    ->icon('link')
                    ->href($post->url)
                    ->target('_blank');
            }),

            TD::make('permalink', 'Джерело')->render(function (Post $post) {
                return Link::make('go')
                    ->icon('link')
                    ->href($post->permalink)
                    ->target('_blank');
            })->defaultHidden(),

            TD::make('width', 'Ширина')->defaultHidden(),

            TD::make('height', 'Висота')->defaultHidden(),

            TD::make('is_nsfw', '18+')->render(function (Post $post) {
                return CheckBox::make('is_nsfw')
                    ->value($post->is_nsfw)
                    ->disabled();
            }),

            TD::make('is_spoiler', 'Спойлер')->render(function (Post $post) {
                return CheckBox::make('is_spoiler')
                    ->value($post->is_spoiler)
                    ->disabled();
            }),

            TD::make('updated_at', __('Last edit'))
                ->sort()
                ->render(fn(Post $post) => $post->updated_at->toDateTimeString()),

            TD::make('Дії')
                ->align(TD::ALIGN_CENTER)
                ->width('100px')
                ->render(fn(Post $post) => DropDown::make()
                    ->icon('options-vertical')
                    ->list([

                        Link::make(__('Edit'))
                            ->route('platform.posts.edit', $post->id)
                            ->icon('pencil'),

                        Button::make(__('Delete'))
                            ->icon('trash')
                            ->confirm(
                                'Якщо ви випадково видалите пост, але захочете його відновити - зверніться до супер адміністратора.'
                            )
                            ->method('remove', [
                                'id' => $post->id,
                            ]),
                    ])),


        ];
    }
}

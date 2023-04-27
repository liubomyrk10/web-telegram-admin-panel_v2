<?php

namespace App\Orchid\Layouts\PostsSchedule;

use App\Models\PostSchedule;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Actions\DropDown;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Layouts\Persona;
use Orchid\Screen\Layouts\Table;
use Orchid\Screen\TD;

class PostScheduleListLayout extends Table
{
    /**
     * Data source.
     *
     * The name of the key to fetch it from the query.
     * The results of which will be elements of the table.
     *
     * @var string
     */
    protected $target = 'posts_schedule';

    /**
     * Get the table cells to be displayed.
     *
     * @return TD[]
     */
    protected function columns(): iterable
    {
        return [
            TD::make('name', __('Name'))
                ->sort()
                ->cantHide()
                ->filter(Input::make())
                ->render(fn(PostSchedule $postsSchedule) => new Persona($postsSchedule->channel->presenter())),


            TD::make('post_id', 'Пост')
                ->render(fn(PostSchedule $postsSchedule) => Link::make($postsSchedule->post->title)
                    ->route('platform.posts.edit', $postsSchedule->post->id)),

            TD::make('post_time', 'Дата та час публікації')
                ->sort()
                ->render(function (PostSchedule $postSchedule) {
                    $datetime = $postSchedule->post_time->toDateTimeString();
                    $now = now();
                    if ($postSchedule->post_time < $now) {
                        return "<div class='alert alert-warning' style='margin: 0' role='alert'>$datetime</div>";
                    }
                    return $datetime;
                }),

            TD::make('updated_at', __('Last edit'))
                ->sort()
                ->render(fn(PostSchedule $postsSchedule) => $postsSchedule->updated_at->toDateTimeString()),

            TD::make('Дії')
                ->align(TD::ALIGN_CENTER)
                ->width('100px')
                ->render(fn(PostSchedule $postsSchedule) => DropDown::make()
                    ->icon('options-vertical')
                    ->list([
                        Link::make('Перегляд')
                            ->route('platform.posts_schedule.card', $postsSchedule->id)
                            ->icon('eye'),

                        Link::make(__('Edit'))
                            ->route('platform.posts_schedule.edit', $postsSchedule->id)
                            ->icon('pencil'),

                        Button::make(__('Delete'))
                            ->icon('trash')
                            ->confirm(
                                'Ви впевнені, що бажаєте видалити заплановану публікацію?'
                            )
                            ->method('remove', [
                                'id' => $postsSchedule->id,
                            ]),
                    ])),
        ];
    }
}

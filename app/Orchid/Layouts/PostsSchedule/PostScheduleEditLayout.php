<?php

namespace App\Orchid\Layouts\PostsSchedule;

use App\Models\Channel;
use App\Models\Post;
use Illuminate\Contracts\Container\BindingResolutionException;
use Orchid\Screen\Field;
use Orchid\Screen\Fields\DateTimer;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\Relation;
use Orchid\Screen\Layouts\Rows;

class PostScheduleEditLayout extends Rows
{
    /**
     * Used to create the title of a group of form elements.
     *
     * @var string|null
     */
    protected $title;

    /**
     * Get the fields elements to be displayed.
     *
     * @return Field[]
     * @throws BindingResolutionException
     */
    protected function fields(): iterable
    {
        return [
            Input::make('post_schedule.id')->type('hidden'),

            Relation::make('post_schedule.channel_id')
                ->fromModel(Channel::class, 'title')
                ->applyScope('onlyOwner')
                ->required()
                ->title('Канал')
                ->help('Вкажіть, в який канал, ви бажаєте відкласти постинг?'),

            Relation::make('post_schedule.post_id')
                ->fromModel(Post::class, 'title')
                ->applyScope('onlyOwner')
                ->searchColumns('title', 'type')
                ->chunk(20)
                ->required()
                ->title('Пост')
                ->help('Який пост потрібно відкласти?'),

            DateTimer::make('post_schedule.post_time')
                ->title('Дата та час публікації')
                ->allowInput()
                ->format24hr()
                ->enableTime()
                ->required(),
        ];
    }
}

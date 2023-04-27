<?php

namespace App\Orchid\Layouts\PostsSchedule;

use App\Models\Bot;
use App\Models\Subreddit;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Field;
use Orchid\Screen\Fields\CheckBox;
use Orchid\Screen\Fields\Label;
use Orchid\Screen\Fields\RadioButtons;
use Orchid\Screen\Fields\Relation;
use Orchid\Screen\Fields\Select;
use Orchid\Screen\Layouts\Rows;

class PostPreviewLayout extends Rows
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
     */
    protected function fields(): iterable
    {
        return [
            RadioButtons::make('post.type')
                ->options([
                    'image' => 'Фото',
                    'video' => 'Відео',
                    'youtube' => 'Youtube',
                    'gif' => 'Гіф',
                    'text' => 'Текст',
                ])
                ->title('Тип')
                ->disabled(),

            Select::make('post.subreddit_id')
                ->fromModel(Subreddit::class, 'name')
                ->title('Субреддіти')
                ->disabled(),

            Relation::make('post.bots.')
                ->fromModel(Bot::class, 'username')
                ->applyScope('onlyOwner')
                ->multiple()
                ->title('Бот')
                ->disabled(),

            Label::make('post.reddit_id')
                ->title('Reddit id'),

            Label::make('post.title')
                ->title('Заголовок'),

            Label::make('post.description')
                ->title('Опис'),

            Label::make('post.url')
                ->title('Контент (url)'),

            Link::make('Переглянути контент')
                ->title('')
                ->icon('link')
                ->canSee($this->query->has('post.url'))
                ->href($this->query->get('post.url') ?? '')
                ->target('_blank'),


            Label::make('post.permalink')
                ->title('Посилання на джерело'),

            Link::make('Переглянути джерело')
                ->icon('link')
                ->canSee($this->query->has('post.permalink'))
                ->href($this->query->get('post.permalink') ?? '')
                ->target('_blank'),

            Label::make('post.width')
                ->title('Ширина'),

            Label::make('post.height')
                ->title('Висота'),

            CheckBox::make('post.is_spoiler')
                ->title('Спойлер')
                ->disabled(),

            CheckBox::make('post.is_nsfw')
                ->title('NSFW')
                ->disabled(),
        ];
    }
}

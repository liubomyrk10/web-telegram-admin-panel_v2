<?php

namespace App\Orchid\Layouts\Post;

use App\Models\Bot;
use App\Models\Subreddit;
use Illuminate\Contracts\Container\BindingResolutionException;
use Orchid\Screen\Field;
use Orchid\Screen\Fields\CheckBox;
use Orchid\Screen\Fields\Label;
use Orchid\Screen\Fields\Relation;
use Orchid\Screen\Fields\Select;
use Orchid\Screen\Layouts\Rows;

class PostMassiveGenerateLayout extends Rows
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
            Select::make('post.parse_type')
                ->options([
                    'new' => 'New',
                    'top' => 'Top',
                    'hot' => 'Hot',
                    'controversial' => 'Controversial',
                ])
                ->title('Тип Reddit постів')
                ->required(),

            Select::make('post.type')
                ->options([
                    'image' => 'Фото',
                    'video' => 'Відео',
                    'youtube' => 'Youtube',
                    'gif' => 'Гіф',
                    'text' => 'Текст',
                ])
                ->title('Тип посту')
                ->required(),

            Relation::make('post.subreddit_id')
                ->fromModel(Subreddit::class, 'name')
                ->required()
                ->title('Субреддіти')
                ->help('Після вибору субреддіта, здійсниться масове збереження постів.'),

            Label::make('post.generate_status')
                ->value('Масове генерація ще не виконувалась.')
                ->class('alert alert-info'),

            Relation::make('post.bots.')
                ->fromModel(Bot::class, 'username')
                ->applyScope('onlyOwner')
                ->multiple()
                ->required()
                ->title('Бот')
                ->help('Вкажіть, які боти будуть мати доступ до постів.'),

            CheckBox::make('post.can_insert')
                ->value(0)
                ->title('Зберегти всі знайдені пости?')
                ->help('Якщо активовано, то вибір субредітта збереже всі знайдені пости.')
                ->sendTrueOrFalse(),
        ];
    }
}

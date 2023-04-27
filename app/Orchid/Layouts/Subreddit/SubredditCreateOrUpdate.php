<?php

namespace App\Orchid\Layouts\Subreddit;

use Orchid\Screen\Field;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\TextArea;
use Orchid\Screen\Layouts\Rows;

class SubredditCreateOrUpdate extends Rows
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
            Input::make('subreddit.id')->type('hidden'),
            Input::make('subreddit.name')->required()->maxlength('20')
                ->help('від 2 до 20 символів')->placeholder("gaming")->title('Назва'),
            TextArea::make('subreddit.description')->required()->placeholder(
                "це спільнота на Reddit, яка призначена для обговорення..."
            )->maxlength('512')->help('до 512 символів')->title('Опис'),
        ];
    }
}

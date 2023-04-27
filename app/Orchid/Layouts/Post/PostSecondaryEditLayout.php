<?php

namespace App\Orchid\Layouts\Post;

use Orchid\Screen\Field;
use Orchid\Screen\Fields\CheckBox;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Layouts\Rows;

class PostSecondaryEditLayout extends Rows
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
            Input::make('post.width')
                ->type('number')
                ->title('Ширина')
                ->placeholder('1920')
                ->help('Розмір контенту по ширині.'),

            Input::make('post.height')
                ->type('number')
                ->title('Висота')
                ->placeholder('1080')
                ->help('Розмір контенту по висоті.'),

            CheckBox::make('post.is_spoiler')
                ->value(0)
                ->title('Спойлер')
                ->help('Це відкриття деталей сюжету.')
                ->sendTrueOrFalse(),

            CheckBox::make('post.is_nsfw')
                ->value(0)
                ->title('NSFW')
                ->help('Контент, для людей старше 18.')
                ->sendTrueOrFalse(),
        ];
    }
}

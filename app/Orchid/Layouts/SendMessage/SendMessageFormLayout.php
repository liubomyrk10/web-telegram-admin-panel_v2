<?php

namespace App\Orchid\Layouts\SendMessage;

use App\Models\Bot;
use Orchid\Screen\Field;
use Orchid\Screen\Fields\Picture;
use Orchid\Screen\Fields\RadioButtons;
use Orchid\Screen\Fields\Relation;
use Orchid\Screen\Fields\SimpleMDE;
use Orchid\Screen\Layouts\Rows;

class SendMessageFormLayout extends Rows
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

        ];
    }
}

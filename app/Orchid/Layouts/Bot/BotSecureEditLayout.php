<?php

namespace App\Orchid\Layouts\Bot;

use Orchid\Screen\Field;
use Orchid\Screen\Fields\CheckBox;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Layouts\Rows;

class BotSecureEditLayout extends Rows
{
    /**
     * Used to create the title of a group of form elements.
     *   telegram_id          bigint unsigned      not null,
     *  user_id              bigint unsigned      not null,
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
            Input::make('bot.telegram_id')
                ->type('number')
                ->maxlength(64)
                ->required()
                ->title('Telegram ID')
                ->placeholder('321022334')
                ->help('експериментальне поле. Поки повторіть Telegram ID власника.'),

            Input::make('bot.token')
                ->type('text')
                ->minlength(40)
                ->maxlength(50)
                ->required()
                ->title('HTTP API token')
                ->placeholder('1234567890:ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghi')
                ->help('видається у @BotFather при створенні бота. Зазвичай 45 символів'),

            CheckBox::make('bot.is_active')
                ->title('Активний?')
                ->value(true)
                ->help('Якщо активний, то відповідає на команди користувача.')
                ->sendTrueOrFalse(),
        ];
    }
}

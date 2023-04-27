<?php

namespace App\Orchid\Layouts\User;

use Orchid\Screen\Field;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Layouts\Rows;

class UserTelegramEditLayout extends Rows
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


            Input::make('user.telegram_id')
                ->type('number')
                ->required()
                ->title('Telegram ID')
                ->placeholder('321022334')
                ->maxlength(64)
                ->help('Отримати user id можна за посиланням: https://www.alphr.com/telegram-find-user-id/'),

            Input::make('user.first_name')
                ->type('text')
                ->max(64)
                ->required()
                ->title("Ім'я")
                ->placeholder("Іван")
                ->help('до 64 символів'),

            Input::make('user.last_name')
                ->type('text')
                ->max(64)
                ->title("Фамілія")
                ->placeholder("Мельник")
                ->help('до 64 символів'),

            Input::make('user.username')
                ->type('text')
                ->max(32)
                ->title("Username (нік)")
                ->placeholder("ivan")
                ->help('до 32 символів'),

            Input::make('user.photo_url')
                ->type('url')
                ->title("Фото (url)")
                ->placeholder("https://telegra.ph/file/722622534aa056f4caa5e.jpg")
                ->help('Окрім класичного посилання, можна використовувати бота - https://t.me/GetImageUrlBot'),
        ];
    }
}

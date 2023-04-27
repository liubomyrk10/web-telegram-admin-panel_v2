<?php

namespace App\Orchid\Layouts\Channel;

use App\Models\Bot;
use Illuminate\Contracts\Container\BindingResolutionException;
use Orchid\Screen\Field;
use Orchid\Screen\Fields\CheckBox;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\Relation;
use Orchid\Screen\Fields\TextArea;
use Orchid\Screen\Layouts\Rows;

class ChannelEditLayout extends Rows
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
            Input::make('channel.id')->type('hidden'),

            Input::make('channel.telegram_id')
                ->type('number')
                ->required()
                ->title('Telegram ID')
                ->placeholder('321022334')
                ->maxlength(64)
                ->help('Отримати user id можна за посиланням: https://www.alphr.com/telegram-find-user-id/'),

            Relation::make('channel.bot_id')
                ->fromModel(Bot::class, 'username')
                ->applyScope('onlyOwner')
                ->required()
                ->title('Бот')
                ->help('Вкажіть, який бот буде виконувати постинг.'),

            Input::make('channel.username')
                ->type('text')
                ->maxlength(32)
                ->required()
                ->title("Username")
                ->placeholder("channel_name")
                ->help('до 32 символів'),

            Input::make('channel.title')
                ->type('text')
                ->max(255)
                ->required()
                ->title("Заголовок")
                ->placeholder("Новини України")
                ->help('до 255 символів'),

            TextArea::make('channel.description')
                ->rows(2)
                ->max(255)
                ->title("Опис")
                ->placeholder(
                    '📰💻🇺🇦 "Українські новини" - це 📢 телеграм канал, який присвячений останнім новинам та подіям в Україні 🇺🇦 та по всьому світу 🌍. Щодня на каналі з\'являються 🆕 оновлення про політику, економіку, культуру, спорт та інші важливі теми, які цікавлять українців та інші аудиторії. 🤓💡'
                )
                ->help('до 255 символів'),

            Input::make('channel.photo')
                ->type('url')
                ->title("Фото (url)")
                ->placeholder("https://telegra.ph/file/722622534aa056f4caa5e.jpg")
                ->help('Окрім класичного посилання, можна використовувати бота - https://t.me/GetImageUrlBot'),

            Input::make('channel.member_count')
                ->type('number')
                ->required()
                ->title('Підписників')
                ->placeholder('10010')
                ->help('Кількість підписників каналу.'),

            CheckBox::make('channel.is_public')
                ->value(1)
                ->title('Публічний')
                ->help('Вкажіть, тип каналу публічний чи приватний.')
                ->sendTrueOrFalse(),

            Input::make('channel.invite_link')
                ->type('url')
                ->title("Invite link (url)")
                ->placeholder("https://t.me/joinchat/AAAAAE3Hq9XjOWQ2Df_Lrg")
                ->help('Вкажіть посилання на вступ до вашого каналу.'),
        ];
    }
}

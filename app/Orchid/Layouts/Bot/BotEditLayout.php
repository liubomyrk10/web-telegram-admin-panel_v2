<?php

namespace App\Orchid\Layouts\Bot;

use App\Models\Subreddit;
use Orchid\Screen\Field;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\Select;
use Orchid\Screen\Fields\TextArea;
use Orchid\Screen\Layouts\Rows;

class BotEditLayout extends Rows
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
            Input::make('bot.id')->type('hidden'),
            Input::make('bot.user_id')->type('hidden')->value(auth()->user()->id),

            Input::make('bot.username')
                ->type('text')
                ->max(32)
                ->required()
                ->title('Нік (username)')
                ->placeholder('username_bot')
                ->help('назва бота з постфіксом "_bot" отриманий від @BotFather. До 32 символів.'),

            Input::make('bot.name')
                ->type('text')
                ->max(64)
                ->required()
                ->title('Назва')
                ->placeholder('Бот Українських Новин')
                ->help('До 64 символів.'),

            TextArea::make('bot.about')
                ->maxlength(120)
                ->rows(3)
                ->required()
                ->title('Про бота')
                ->placeholder(
                    'Привіт! Я Бот Українських новин 🗞️🤖 Моя мета - допомогти тобі бути в курсі найсвіжіших новин України та світу.'
                )
                ->help('До 120 символів.'),

            TextArea::make('bot.description')
                ->maxlength(512)
                ->rows(5)
                ->required()
                ->title('Про бота')
                ->placeholder(
                    'Привіт! Я - бот Українських новин. За допомогою цього бота ви зможете отримувати свіжі новини з різних джерел на українській мові. Для того, щоб почати користуватися ботом, достатньо його запустити та вибрати потрібний розділ з новинами. Бот пропонує кілька категорій новин, зокрема політичні новини, новини зі світу технологій, культури та спорту. Новини оновлюються автоматично, тому ви можете бути впевнені, що завжди будете в курсі останніх подій. Насолоджуйтеся користуванням ботом та будьте завжди в курсі!'
                )
                ->help('До 512 символів.'),

            Input::make('bot.avatar')
                ->type('url')
                ->title("Аватар (url)")
                ->placeholder("https://telegra.ph/file/722622534aa056f4caa5e.jpg")
                ->help('Окрім класичного посилання, можна використовувати бота - https://t.me/GetImageUrlBot'),

            Select::make('bot.subreddits.')
                ->fromModel(Subreddit::class, 'name')
                ->multiple()
                ->title('Субреддіти')
                ->help('Вкажіть, які субреддіти підтримує бот.'),

            TextArea::make('bot.welcome_message_text')
                ->maxlength(4096)
                ->rows(6)
                ->title('Вітальне повідомлення')
                ->placeholder(
                    'Привіт! Я Бот Українських новин 🗞️🤖 Моя мета - допомогти тобі бути в курсі найсвіжіших новин України та світу. Відтепер ти можеш отримувати оновлення в режимі реального часу, не відходячи від свого телефону! 😊 Для того, щоб підписатися на новини, просто напиши /subscribe. Якщо виникнуть питання, можеш написати /help.'
                )
                ->help('Це те, що бот відправляє користувачу по команді /start. До 4096 символів.'),

            TextArea::make('bot.help_message_text')
                ->maxlength(4096)
                ->rows(8)
                ->title('Повідомлення допомоги')
                ->placeholder(
                    "Привіт! Я - бот Українських новин 📰🤖

Якщо ти хочеш дізнатися останні новини з України та світу, просто відправ мені повідомлення і я надішлю тобі головні новини дня 📩

Крім того, я розумію такі команди:
/about - інформація про бота
/subscribe - підписатися на розсилку новин
/unsubscribe - відписатися від розсилки новин

Не соромся написати мені, я завжди радий спілкуванню з тобою! 💬"
                )
                ->help('Це те, що бот відправляє користувачу по команді /start. До 4096 символів.'),
        ];
    }
}

<?php

namespace App\Orchid\Layouts\Subscriber;

use App\Models\Subscriber;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Fields\CheckBox;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Layouts\Table;
use Orchid\Screen\TD;

class SubscriberListLayout extends Table
{
    /**
     * Data source.
     *
     * The name of the key to fetch it from the query.
     * The results of which will be elements of the table.
     *
     * @var string
     */
    protected $target = 'subscribers';

    /**
     * Get the table cells to be displayed.
     *
     * @return TD[]
     */
    protected function columns(): iterable
    {
        return [
            TD::make('telegram_id', __('Telegram ID')),

            TD::make('first_name', 'Ім\'я'),
            TD::make('last_name', 'Фамілія')->defaultHidden(),
            TD::make('lang', 'Мова інтерфейсу'),

            TD::make('username', __('Username'))->filter(Input::make())->sort(),

            TD::make('bot_id', 'Бот')
                ->render(fn(Subscriber $subscriber) => $subscriber->bot->name)
                ->defaultHidden(),

            TD::make('is_blocked', 'Заблокував')->render(function (Subscriber $subscriber) {
                return CheckBox::make('is_blocked')
                    ->value($subscriber->is_blocked)
                    ->disabled();
            }),

            TD::make('created_at', 'Дата та час підписки')
                ->sort()
                ->render(fn(Subscriber $subscriber) => $subscriber->created_at->toDateTimeString()),

            TD::make('Дія')
                ->align(TD::ALIGN_CENTER)
                ->width('100px')
                ->render(fn(Subscriber $subscriber) => Link::make('Перегляд')
                    ->route('platform.subscribers.card', ['subscriber' => $subscriber->id])
                    ->icon('eye'),
                ),
        ];
    }
}

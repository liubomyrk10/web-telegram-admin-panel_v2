<?php

namespace App\Orchid\Screens\Log;

use App\Models\Log;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Screen;
use Orchid\Screen\Sight;
use Orchid\Support\Facades\Layout;

class LogCardScreen extends Screen
{

    /**
     * Fetch data to be displayed on the screen.
     *
     * @return array
     */
    public function query(Log $log): iterable
    {
        return [
            'log' => $log
        ];
    }

    /**
     * The name of the screen displayed in the header.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return 'Лог';
    }

    /**
     * Display header description.
     *
     * @return string|null
     */
    public function description(): ?string
    {
        return "Розгорнута інформація про лог (історичну подію)";
    }

    /**
     * The screen's action buttons.
     *
     * @return \Orchid\Screen\Action[]
     */
    public function commandBar(): iterable
    {
        return [];
    }

    /**
     * The screen's layout elements.
     *
     * @return \Orchid\Screen\Layout[]|string[]
     */
    public function layout(): iterable
    {
        return [
            Layout::legend('log', [
                Sight::make('id')->popover('Ідентифікатор, символ, який однозначно ідентифікує об\'єкт або запис'),
                Sight::make('subscriber_id')->render(function (Log $log) {
                    return Link::make($log->subscriber->full_name)
                        ->route('platform.subscribers.edit', $log->subscriber->id)
                        ->icon('eye');
                }),
                Sight::make('chat_type', 'Тип чату'),
                Sight::make('command', 'Команда'),
                Sight::make('message', 'Повідомлення'),
                Sight::make('send_time_taken_in_seconds', 'Витрачений час в секундах'),
                Sight::make('created_at', 'Дата та час створення'),
                Sight::make('updated_at', 'Дата та час оновлення'),
            ])->title('Лог'),
        ];
    }
}

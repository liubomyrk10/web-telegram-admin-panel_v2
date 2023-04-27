<?php

namespace App\Orchid\Screens\Subscriber;

use App\Models\Subscriber;
use Orchid\Screen\Action;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Screen;
use Orchid\Screen\Sight;
use Orchid\Support\Facades\Layout;

class SubscriberCardScreen extends Screen
{

    /**
     * Fetch data to be displayed on the screen.
     *
     * @return array
     */
    public function query(Subscriber $subscriber): iterable
    {
        return [
            'subscriber' => $subscriber
        ];
    }

    /**
     * The name of the screen displayed in the header.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return 'Підписник';
    }

    /**
     * Display header description.
     *
     * @return string|null
     */
    public function description(): ?string
    {
        return "Розгорнута інформація про підписника";
    }

    /**
     * The screen's action buttons.
     *
     * @return Action[]
     */
    public function commandBar(): iterable
    {
        return [];
    }

    /**
     * The screen's layout elements.
     *
     * @return Layout[]|string[]
     */
    public function layout(): iterable
    {
        return [
            Layout::legend('subscriber', [
                Sight::make('id')->popover('Ідентифікатор, символ, який однозначно ідентифікує об\'єкт або запис'),
                Sight::make('bot_id')->render(function (Subscriber $subscriber) {
                    return Link::make($subscriber->bot->name)
                        ->route('platform.bots.edit', $subscriber->bot->id)
                        ->icon('eye');
                }),
                Sight::make('first_name', 'Ім\'я'),
                Sight::make('last_name', 'Прізвище'),
                Sight::make('username', 'Username'),
                Sight::make('lang', 'Мова'),
                Sight::make('is_blocked', 'Заблокував')->render(
                    fn(Subscriber $subscriber) => !$subscriber->is_blocked
                        ? '<i class="text-success">●</i> False'
                        : '<i class="text-danger">●</i> True'
                ),
                Sight::make('created_at', 'Created'),
                Sight::make('updated_at', 'Updated'),
            ])->title('Підписник'),
        ];
    }
}

<?php

namespace App\Orchid\Screens\Subscriber;

use App\Models\Subscriber;
use App\Orchid\Layouts\Subscriber\SubscriberListLayout;
use Orchid\Screen\Action;
use Orchid\Screen\Layout;
use Orchid\Screen\Screen;

class SubscriberListScreen extends Screen
{
    /**
     * Fetch data to be displayed on the screen.
     *
     * @return array
     */
    public function query(): iterable
    {
        return [
            'subscribers' => Subscriber::whereHas('bot', function ($query) {
                $query->where('user_id', auth()->user()->id);
            })->filters()->defaultSort('created_at', 'desc')->paginate()
        ];
    }

    /**
     * The name of the screen displayed in the header.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return 'Підписники';
    }

    /**
     * Display header description.
     *
     * @return string|null
     */
    public function description(): ?string
    {
        return 'Загальний список підписників з всіх ботів, без дублікатів.';
    }


    /**
     * @return iterable|null
     */
    public function permission(): ?iterable
    {
        return [
            'platform.subscribers',
        ];
    }

    /**
     * The screen's action buttons.
     *
     * @return Action[]
     */
    public function commandBar(): iterable
    {
        return [
            /*            Link::make(__('Add'))
                            ->icon('plus')
                            ->route('platform.subscribers.create'),*/
        ];
    }

    /**
     * The screen's layout elements.
     *
     * @return Layout[]|string[]
     */
    public function layout(): iterable
    {
        return [
            SubscriberListLayout::class,
        ];
    }
}

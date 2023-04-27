<?php

namespace App\Orchid\Screens\Channel;

use App\Models\Channel;
use App\Orchid\Layouts\Channel\ChannelListLayout;
use Illuminate\Http\Request;
use Orchid\Screen\Action;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Layout;
use Orchid\Screen\Screen;
use Orchid\Support\Facades\Toast;

class ChannelListScreen extends Screen
{
    /**
     * Fetch data to be displayed on the screen.
     *
     * @return array
     */
    public function query(): iterable
    {
        return [
            'channels' => Channel::whereHas('bot', function ($query) {
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
        return 'Канали';
    }

    /**
     * Display header description.
     *
     * @return string|null
     */
    public function description(): ?string
    {
        return 'Керування вашим списком каналів.';
    }

    /**
     * @return iterable|null
     */
    public function permission(): ?iterable
    {
        return [
            'platform.channels',
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
            Link::make(__('Add'))
                ->icon('plus')
                ->route('platform.channels.create'),
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
            ChannelListLayout::class,
        ];
    }

    /**
     * @param Request $request
     */
    public function remove(Request $request): void
    {
        Channel::query()->findOrFail($request->get('id'))->delete();
        Toast::info('Канал видалено успішно!');
    }
}

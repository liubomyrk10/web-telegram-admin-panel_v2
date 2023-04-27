<?php

namespace App\Orchid\Screens\Log;

use App\Models\Log;
use App\Orchid\Layouts\Log\LogListLayout;
use Orchid\Screen\Screen;
use Orchid\Support\Facades\Toast;

class LogListScreen extends Screen
{
    /**
     * Fetch data to be displayed on the screen.
     *
     * @return array
     */
    public function query(): iterable
    {
        return [
            'logs' => Log::with('subscriber.bot.user')
                ->whereHas('subscriber.bot.user', function ($query) {
                    $query->where('id', auth()->user()->id);
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
        return 'Логи';
    }

    /**
     * Display header description.
     *
     * @return string|null
     */
    public function description(): ?string
    {
        return 'Історія використання ботів.';
    }

    /**
     * @return iterable|null
     */
    public function permission(): ?iterable
    {
        return [
            'platform.logs',
        ];
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
            LogListLayout::class,
        ];
    }

    /*    public function delete(Request $request)
        {
            $id = $request->input('log.id');
            Log::query()->find($id)->delete();
            Toast::info('Лог успішно видалений!');
        }*/

    public function remove(Log $log)
    {
        $log->delete();

        Toast::info('Лог успішно видалений!');

        return redirect()->route('platform.bots');
    }
}

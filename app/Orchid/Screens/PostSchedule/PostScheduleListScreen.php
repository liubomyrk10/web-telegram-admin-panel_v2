<?php

namespace App\Orchid\Screens\PostSchedule;

use App\Models\PostSchedule;
use App\Orchid\Layouts\PostsSchedule\PostScheduleListLayout;
use Illuminate\Support\Facades\Request;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Screen;
use Orchid\Support\Facades\Toast;

class PostScheduleListScreen extends Screen
{

    public function query(): iterable
    {
        return [
            'posts_schedule' => PostSchedule::with('post', 'channel')->whereHas('channel.bot', function ($query) {
                $query->where('user_id', auth()->user()->id);
            })->filters()->defaultSort('created_at', 'desc')->paginate()
        ];
    }

    public function name(): ?string
    {
        return 'Відкладені пости';
    }

    public function description(): ?string
    {
        return 'Список відкладених постів для телеграм каналів.';
    }

    public function permission(): ?iterable
    {
        return [
            'platform.posts_schedule',
        ];
    }

    public function commandBar(): iterable
    {
        return [
            Link::make(__('Add'))
                ->icon('plus')
                ->route('platform.posts_schedule.create'),
        ];
    }

    public function layout(): iterable
    {
        return [
            PostScheduleListLayout::class
        ];
    }

    public function remove(Request $request): void
    {
        PostSchedule::query()->findOrFail($request->get('id'))->delete();
        Toast::info('Відкладений пост видалено успішно!');
    }
}

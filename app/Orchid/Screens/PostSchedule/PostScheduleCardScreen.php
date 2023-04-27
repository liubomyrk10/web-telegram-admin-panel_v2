<?php

namespace App\Orchid\Screens\PostSchedule;

use App\Models\Channel;
use App\Models\Post;
use App\Models\PostSchedule;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Screen;
use Orchid\Screen\Sight;
use Orchid\Support\Facades\Layout;

class PostScheduleCardScreen extends Screen
{
    /**
     * Fetch data to be displayed on the screen.
     *
     * @return array
     */
    public function query(PostSchedule $postSchedule): iterable
    {
        return [
            'post_schedule' => $postSchedule,
            'channel' => $postSchedule->channel,
            'post' => $postSchedule->post
        ];
    }

    /**
     * The name of the screen displayed in the header.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return 'Відкладений пост';
    }

    public function description(): ?string
    {
        return "Розгорнута інформація про відкладений пост.";
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
            Layout::legend('post_schedule', [
                Sight::make('id')->popover('Ідентифікатор, символ, який однозначно ідентифікує об\'єкт або запис'),
                Sight::make('channel_id')->render(function (PostSchedule $postSchedule) {
                    return Link::make($postSchedule->channel->title)
                        ->route('platform.channels.edit', $postSchedule->channel->id)
                        ->icon('eye');
                }),
                Sight::make('post_id')->render(function (PostSchedule $postSchedule) {
                    return Link::make($postSchedule->post->title)
                        ->route('platform.posts.edit', $postSchedule->post->id)
                        ->icon('eye');
                }),
                Sight::make('post_time', 'Дата та час публікування'),
                Sight::make('created_at', 'Дата та час створення'),
                Sight::make('updated_at', 'Дата та час оновлення'),
            ])->title('Відкладений пост'),

            Layout::legend('channel', [
                Sight::make('id')->popover('Ідентифікатор, символ, який однозначно ідентифікує об\'єкт або запис'),
                Sight::make('bot_id')->render(function (Channel $channel) {
                    return Link::make($channel->bot->username)
                        ->route('platform.bots.edit', $channel->bot->id)
                        ->icon('eye');
                }),
                Sight::make('title', 'Заголовок'),
                Sight::make('username', 'Username'),
                Sight::make('description', 'Опис'),
                Sight::make('photo')->render(function (Channel $channel) {
                    return "<img href=\"{$channel->photo}\" width=\"100\" height=\"100\" />";
                }),
                Sight::make('member_count', 'Кількість підписників'),
                Sight::make('is_public', 'Публічний канал'),
                Sight::make('invite_link')->render(function (Channel $channel) {
                    return Link::make('')
                        ->href($channel->invite_link)
                        ->icon('eye');
                }),
                Sight::make('created_at', 'Дата та час створення'),
                Sight::make('updated_at', 'Дата та час оновлення'),
            ])->title('Канал, куди запланований пост'),

            Layout::legend('post', [
                Sight::make('id')->popover('Ідентифікатор, символ, який однозначно ідентифікує об\'єкт або запис'),
                Sight::make('reddit_id', 'Заголовок'),
                Sight::make('subreddit_id')->render(fn(Post $post) => $post->subreddit->name
                ),
                Sight::make('type', 'Тип'),
                Sight::make('title', 'Заголовок'),
                Sight::make('description', 'Опис'),
                Sight::make('url')->render(function (Post $post) {
                    return Link::make('')
                        ->href($post->url)
                        ->icon('eye');
                }),
                Sight::make('permalink')->render(function (Post $post) {
                    return Link::make('')
                        ->href($post->permalink)
                        ->icon('eye');
                }),
                Sight::make('width', 'Ширина'),
                Sight::make('height', 'Висота'),
                Sight::make('is_nsfw', 'NSFW'),
                Sight::make('is_spoiler', 'Спойлер'),
                Sight::make('created_at', 'Дата та час створення'),
                Sight::make('updated_at', 'Дата та час оновлення'),
            ])->title('Пост, який і буде публікуватись'),
        ];
    }
}

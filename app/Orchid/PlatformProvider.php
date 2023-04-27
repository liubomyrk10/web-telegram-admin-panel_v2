<?php

declare(strict_types=1);

namespace App\Orchid;

use App\Models\Bot;
use App\Models\Channel;
use App\Models\Log;
use App\Models\Post;
use App\Models\PostSchedule;
use App\Models\Subreddit;
use App\Models\Subscriber;
use Illuminate\Support\Facades\Config;
use Orchid\Platform\Dashboard;
use Orchid\Platform\ItemPermission;
use Orchid\Platform\OrchidServiceProvider;
use Orchid\Screen\Actions\Menu;
use Orchid\Support\Color;

class PlatformProvider extends OrchidServiceProvider
{
    /**
     * @param Dashboard $dashboard
     */
    public function boot(Dashboard $dashboard): void
    {
        parent::boot($dashboard);
        $this->setUserTimeZone();
    }

    /**
     * @return Menu[]
     */
    public function registerMainMenu(): array
    {
        return [
            Menu::make('Головна')
                ->icon('home')
                ->title('Головна')
                ->route('platform.index'),

            Menu::make('Відкладені пости')
                ->icon('hourglass')
                ->title('Автопостинг')
                ->route('platform.posts_schedule')
                ->badge(fn() => PostSchedule::countByOwner(), Color::DARK()),

            Menu::make('Канали')
                ->icon('paper-plane')
                ->route('platform.channels')
                ->divider()
                ->badge(fn() => Channel::countByOwner(), Color::DARK()),

            Menu::make('Боти')
                ->icon('android')
                ->title('Поведінка ботів')
                ->route('platform.bots')
                ->badge(fn() => Bot::countByOwner(), Color::DARK()),

            Menu::make('Логи')
                ->icon('history')
                ->route('platform.logs')
                ->badge(fn() => Log::countByOwner(), Color::DARK()),

            Menu::make('Підписники')
                ->icon('friends')
                ->route('platform.subscribers')
                ->badge(fn() => Subscriber::countByOwner(), Color::DARK()),

            Menu::make('Розсилка')
                ->icon('envelope-letter')
                ->route('platform.send_message')
                ->divider(),

            Menu::make('Пости')
                ->icon('grid')
                ->route('platform.posts')
                ->title('Контент')
                ->badge(fn() => Post::countByOwner(), Color::DARK()),

            Menu::make('Субреддіти')
                ->icon('social-reddit')
                ->route('platform.subreddits')
                ->divider()
                ->badge(fn() => Subreddit::count(), Color::DARK()),
            /*
                        Menu::make('Dropdown menu')
                            ->icon('code')
                            ->list([
                                Menu::make('Sub element item 1')->icon('bag'),
                                Menu::make('Sub element item 2')->icon('heart'),
                            ]),

                        Menu::make('Basic Elements')
                            ->title('Form controls')
                            ->icon('note')
                            ->route('platform.example.fields'),

                        Menu::make('Advanced Elements')
                            ->icon('briefcase')
                            ->route('platform.example.advanced'),

                        Menu::make('Text Editors')
                            ->icon('list')
                            ->route('platform.example.editors'),

                        Menu::make('Overview layouts')
                            ->title('Layouts')
                            ->icon('layers')
                            ->route('platform.example.layouts'),

                        Menu::make('Chart tools')
                            ->icon('bar-chart')
                            ->route('platform.example.charts'),

                        Menu::make('Cards')
                            ->icon('grid')
                            ->route('platform.example.cards')
                            ->divider(),

                        Menu::make('Documentation')
                            ->title('Docs')
                            ->icon('docs')
                            ->url('https://orchid.software/en/docs'),

                        Menu::make('Changelog')
                            ->icon('shuffle')
                            ->url('https://github.com/orchidsoftware/platform/blob/master/CHANGELOG.md')
                            ->target('_blank')
                            ->badge(fn() => Dashboard::version(), Color::DARK()),*/

            Menu::make(__('Users'))
                ->icon('user')
                ->route('platform.systems.users')
                ->permission('platform.systems.users')
                ->title(__('Access rights')),

            Menu::make(__('Roles'))
                ->icon('lock')
                ->route('platform.systems.roles')
                ->permission('platform.systems.roles'),
        ];
    }

    /**
     * @return Menu[]
     */
    public function registerProfileMenu(): array
    {
        return [
            Menu::make(__('Profile'))
                ->route('platform.profile')
                ->icon('user'),
        ];
    }

    /**
     * @return ItemPermission[]
     */
    public function registerPermissions(): array
    {
        return [
            ItemPermission::group(__('System'))
                ->addPermission('platform.systems.roles', __('Roles'))
                ->addPermission('platform.systems.users', __('Users')),
            ItemPermission::group('Telegram')
                ->addPermission('platform.bots', 'Боти')
                ->addPermission('platform.logs', 'Логи')
                ->addPermission('platform.posts', 'Пости')
                ->addPermission('platform.channels', 'Канали')
                ->addPermission('platform.posts_schedule', 'Розклад постів')
                ->addPermission('platform.subreddits', 'Субреддіти')
                ->addPermission('platform.subscribers', 'Підписники')
                ->addPermission('platform.send_message', 'Розсилка повідомлень')
        ];
    }

    /**
     * Визначаємо і перезаписуєм налаштування timezone по браузеру користувача.
     */
    private function setUserTimeZone(): void
    {
        $timeZone = request()->header('timezone');
        if ($timeZone) {
            Config::set('app.timezone', $timeZone);
        }
    }
}

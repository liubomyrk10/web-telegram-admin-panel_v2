<?php

declare(strict_types=1);

namespace App\Orchid\Screens;

use App\Models\Log;
use App\Models\Post;
use App\Models\PostSchedule;
use App\Models\Subscriber;
use App\Orchid\Layouts\Charts\LogChart;
use App\Orchid\Layouts\Charts\PostChart;
use App\Orchid\Layouts\Charts\SubscriberChart;
use Illuminate\Support\Carbon;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Screen;
use Orchid\Support\Facades\Layout;

class PlatformScreen extends Screen
{
    /**
     * Fetch data to be displayed on the screen.
     *
     * @return array
     */
    public function query(): iterable
    {
        $postsCount = $this->getPostsCountByWeak();


        return [
            'metrics' => [
                'subscribers' => [
                    'value' => number_format((float)Subscriber::countByOwner()),
                    'diff' => Subscriber::weakCountByOwner()
                ],
                'posts' => [
                    'value' => number_format((float)Post::countByOwner()),
                    'diff' => Post::weakCountByOwner()
                ],
                'logs' => [
                    'value' => number_format((float)Log::countByOwner()),
                    'diff' => Log::weakCountByOwner()
                ],
                'scheduled_posts' => [
                    'value' => number_format((float)PostSchedule::countByOwner()),
                    'diff' => PostSchedule::PostedCountByOwner()
                ],
            ],

            'logCommandsChart' => Log::onlyOwner()->countForGroup('command')->toChart(),
            'subscriberDynamicChart' => [
                Subscriber::onlyOwner()->countByDays(
                    Carbon::today()->subDays(30),
                    Carbon::today(),
                    'created_at'
                )->toChart('Підписники ботів'),
            ],
            'postChart' => [
                Post::onlyOwner()->countByDays(
                    Carbon::today()->subDays(30),
                    Carbon::today(),
                    'created_at'
                )->toChart('Історія постів'),
            ],

        ];
    }

    /**
     * The name of the screen displayed in the header.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return 'Dashboard';
    }

    /**
     * Display header description.
     *
     * @return string|null
     */
    public function description(): ?string
    {
        return 'Веб додаток для створення і управлінням телеграм ботом';
    }

    /**
     * The screen's action buttons.
     *
     * @return \Orchid\Screen\Action[]
     */
    public function commandBar(): iterable
    {
        return [

            Link::make('Telegram API Documentation')
                ->href('https://core.telegram.org/bots/api')
                ->icon('docs'),

            Link::make('GitHub')
                ->href('https://github.com/')
                ->icon('social-github'),
        ];
    }

    /**
     * The screen's layout elements.
     *
     * @return \Orchid\Screen\Layout[]
     */
    public function layout(): iterable
    {
        return [
            Layout::metrics([
                'Підписників За Тиждень' => 'metrics.subscribers',
                'Постів' => 'metrics.posts',
                'Дії Користувачів' => 'metrics.logs',
                'Відкладених постів' => 'metrics.scheduled_posts',
            ]),
            Layout::columns([
                LogChart::class,
                SubscriberChart::class,
            ]),
            PostChart::class,
            /*Layout::view('platform.main'),*/
        ];
    }

    private function getPostsCountByWeak()
    {
        $startDate = Carbon::today()->subDays(7);
        $endDate = Carbon::today();
        return Post::whereBetween('created_at', [$startDate, $endDate])->count();
    }
}

<?php

namespace App\Orchid\Screens\SendMessage;

use App\Jobs\TelegramMailerJob;
use App\Jobs\TelegramSendMessageJob;
use App\Models\Bot;
use App\Orchid\Layouts\SendMessage\SendMessageFormListener;
use App\Services\Post\PostTypeEnum;
use Illuminate\Http\Request;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Screen;
use Orchid\Support\Color;
use Orchid\Support\Facades\Layout;
use Telegram\Bot\BotsManager;

class SendMessageScreen extends Screen
{
    public function __construct(private BotsManager $telegram)
    {
    }

    /**
     * Fetch data to be displayed on the screen.
     *
     * @return array
     */
    public function query(): iterable
    {
        return [];
    }

    /**
     * The name of the screen displayed in the header.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return 'Розсилка повідомлень';
    }

    public function description(): ?string
    {
        return "Можливість написати кожному підписнику бота.";
    }


    public function permission(): ?iterable
    {
        return [
            'platform.send_message',
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
            Layout::block(SendMessageFormListener::class)
                ->title('Форма відправки (бета)')
                ->description('Всім підписникам обраного бота.')
                ->commands(
                    Button::make('Надіслати')
                        ->type(Color::INFO())
                        ->icon('check')
                        ->method('send')
                ),
        ];
    }

    public function send(Request $request)
    {
        ['bot' => $botId, 'body' => $body, 'type' => $type] = $request['send_message'];
        $bot = Bot::with('subscribers')->find($botId);
        $telegram = $this->telegram->bot($bot->username);
        $postTypeEnum = PostTypeEnum::from($type);

        foreach ($bot->subscribers as $subscriber) {
            dispatch(
                new TelegramSendMessageJob(
                    $postTypeEnum,
                    $telegram,
                    $subscriber,
                    [
                        'body' => $body,
                        'filePath' => match ($postTypeEnum) {
                            PostTypeEnum::TEXT => null,
                            PostTypeEnum::IMAGE => $request['send_message']['image'],
                            PostTypeEnum::GIF => $request['send_message']['gif'],
                            PostTypeEnum::VIDEO => $request['send_message']['video'],
                        }
                    ]
                )
            );
        }
        \Illuminate\Support\Facades\Artisan::call('queue:work');
    }

    public function asyncFormBuilder(Request $request)
    {
        ['type' => $type] = $request['send_message'];
        $postTypeEnum = PostTypeEnum::from($type);

        return match ($postTypeEnum) {
            PostTypeEnum::IMAGE => [
                'send_message.type' => $type,
                'image' => true
            ],
            PostTypeEnum::GIF => [
                'send_message.type' => $type,
                'gif' => true
            ],
            PostTypeEnum::VIDEO => [
                'send_message.type' => $type,
                'video' => true
            ],
            default => [
                'send_message.type' => $type,
                'text' => true
            ]
        };
    }
}

<?php

namespace App\Orchid\Screens\Bot;

use App\Http\Requests\BotRequest;
use App\Models\Bot;
use App\Orchid\Layouts\Bot\BotEditLayout;
use App\Orchid\Layouts\Bot\BotSecureEditLayout;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Screen;
use Orchid\Support\Color;
use Orchid\Support\Facades\Layout;
use Orchid\Support\Facades\Toast;
use Telegram\Bot\BotsManager;
use Telegram\Bot\Exceptions\TelegramSDKException;

class BotEditScreen extends Screen
{
    /**
     * @var Bot
     */
    public Bot $bot;

    public function __construct(private BotsManager $telegram)
    {
    }

    /**
     * Fetch data to be displayed on the screen.
     *
     * @return array
     */
    public function query(Bot $bot): iterable
    {
        $bot->load(['subreddits']);

        return [
            'bot' => $bot
        ];
    }

    /**
     * The name of the screen displayed in the header.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return $this->bot->exists ? 'Редагувати бота' : 'Додати бота';
    }

    /**
     * Display header description.
     *
     * @return string|null
     */
    public function description(): ?string
    {
        return "Поля даних вашого бота. Не показуйте HTTP API token";
    }

    /**
     * @return iterable|null
     */
    public function permission(): ?iterable
    {
        return [
            'platform.bots',
        ];
    }

    /**
     * The screen's action buttons.
     *
     * @return \Orchid\Screen\Action[]
     */
    public function commandBar(): iterable
    {
        return [
            Button::make(__('Remove'))
                ->icon('trash')
                ->confirm(
                    'Якщо ви випадково видалите бота, але захочете його відновити - зверніться до супер адміністратора.'
                )
                ->method('remove')
                ->canSee($this->bot->exists),

            Button::make(__('Save'))
                ->icon('check')
                ->method('save'),
        ];
    }

    /**
     * The screen's layout elements.
     *
     * @return \Orchid\Screen\Layout[]|string[]
     */
    public function layout(): iterable
    {
        return [
            Layout::block(BotEditLayout::class)
                ->title('Інформація про бота')
                ->description('Оновіть інформацію про вашого бота.')
                ->commands(
                    Button::make(__('Save'))
                        ->type(Color::DEFAULT())
                        ->icon('check')
                        ->canSee($this->bot->exists)
                        ->method('save')
                ),

            Layout::block(BotSecureEditLayout::class)
                ->title('Важлива інформація про бота')
                ->description('Важлива інформація про бота.')
                ->commands(
                    Button::make(__('Save'))
                        ->type(Color::DEFAULT())
                        ->icon('check')
                        ->canSee($this->bot->exists)
                        ->method('save')
                ),
        ];
    }

    public function save(Bot $bot, BotRequest $request)
    {
        $validatedBot = $request->validated()['bot'];
        $bot->telegram_id = $validatedBot['telegram_id'];
        $bot->user_id = $validatedBot['user_id'];
        $bot->username = $validatedBot['username'];
        $bot->name = $validatedBot['name'];
        $bot->about = $validatedBot['about'];
        $bot->description = $validatedBot['description'];
        $bot->avatar = $validatedBot['avatar'];
        $bot->token = $validatedBot['token'];
        $bot->is_active = $validatedBot['is_active'];
        $bot->welcome_message_text = $validatedBot['welcome_message_text'];
        $bot->help_message_text = $validatedBot['help_message_text'];

        $bot->save();
        $bot->subreddits()->sync($validatedBot['subreddits']);

        $this->setWebHooksForAllBots();

        Toast::info('Бота успішно збережено! WebHook успішно встановлено!');

        return redirect()->route('platform.bots');
    }


    public function remove(Bot $bot)
    {
        $bot->delete();

        Toast::info('Бота було успішно видалено!');

        return redirect()->route('platform.bots');
    }

    /**
     * Задаємо WebHook для нового бота, та всіх вже наявних.
     * @throws TelegramSDKException
     */
    private function setWebHooksForAllBots()
    {
        foreach ($this->telegram->getConfig('bots') as $botName => $bot) {
            if ($botName == 'mybot') {
                continue;
            }
            $api = $this->telegram->bot($botName);
            $api->setWebhook(['url' => $bot['webhook_url']]);
        }
    }
}

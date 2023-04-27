<?php

namespace App\Commands;

use App\Models\Bot;
use App\Models\Subscriber;
use Telegram\Bot\Commands\Command;
use Telegram\Bot\Exceptions\TelegramSDKException;

class StartCommand extends Command
{
    private Bot $bot;
    protected string $name = 'start';
    protected string $description = 'почати роботу з ботом / start working with the bot';

    public function handle(): void
    {
        $username = $this->telegram->getMe()->username;
        $this->bot = Bot::findByUsername($username);

        $text = $this->bot->welcome_message_text . "[­](" . $this->bot->avatar . ")";

        $this->replyWithMessage(
            [
                'text' => $text,
                'parse_mode' => 'Markdown',
            ]
        );
        $this->subscribe();
    }

    /**
     *
     * @throws TelegramSDKException
     */
    private function subscribe()
    {
        $subscriberResponse = $this->getUpdate()->getMessage()->from;
        Subscriber::firstOrCreate(
            ['telegram_id' => $subscriberResponse->id],
            [
                'bot_id' => $this->bot->id,
                'first_name' => $subscriberResponse->first_name,
                'last_name' => $subscriberResponse->last_name,
                'username' => $subscriberResponse->username,
                'lang' => $subscriberResponse->language_code,
                'is_blocked' => false
            ]
        );
    }

    private function blockChecking(): bool
    {
        $id = $this->getUpdate()->getMessage()->from->id;
        $member = $this->telegram->getChatMember([
            'chat_id' => $id,
            'user_id' => $this->telegram->getMe()->getId(),
        ]);

        return $member->isBlocked();
    }
}

<?php

namespace App\Commands;

use App\Models\Bot;
use App\Models\PostSchedule;
use Telegram;
use Telegram\Bot\Commands\Command;

class HelpCommand extends Command
{
    protected string $name = 'help';
    protected array $aliases = ['listcommands'];
    protected string $description = 'дізнатися більше про команди / learn more about the commands';

    public function handle(): void
    {
        $username = $this->telegram->getMe()->username;
        $bot = Bot::findByUsername($username);

        $commands = $this->telegram->getCommandBus()->getCommands();

        $text = $bot->help_message_text . "\n\nя розумію такі команди:";
        foreach ($commands as $name => $handler) {
            $text .= sprintf('/%s - %s' . PHP_EOL, $name, $handler->getDescription());
        }

        $this->replyWithMessage(
            [
                'text' => $text,
                'parse_mode' => 'Markdown'
            ]
        );
    }
}

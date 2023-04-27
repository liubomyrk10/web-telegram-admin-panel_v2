<?php

namespace App\Commands;

use App\Models\Bot;
use App\Models\Log;
use App\Models\Subscriber;
use App\Services\Post\PostService;
use App\Services\Post\PostTypeEnum;
use Illuminate\Support\Facades\Date;
use Telegram\Bot\Actions;
use Telegram\Bot\Commands\Command;
use Telegram\Bot\Exceptions\TelegramSDKException;
use Telegram\Bot\FileUpload\InputFile;

class ImageCommand extends Command
{
    protected string $name = 'image';
    protected string $description = 'надсилає пост картинку / sends a picture post';

    /**
     * @throws TelegramSDKException
     */
    public function handle(): void
    {
        // Засікаємо початковий час виконання запиту
        $start = Date::now();

        $chatId = (string)$this->getUpdate()->getChat()->id;
        $username = $this->telegram->getMe()->username;
        $bot = Bot::findByUsername($username);
        $post = PostService::getRandomPostFromBotByType($bot, PostTypeEnum::from($this->name));

        if (is_null($post)) {
            $this->replyWithMessage([
                'text' => 'Нажаль, нам не вдалось знайти контент😞, спробуйте іншу команду - /help!'
            ]);
            return;
        }

        $inviteLink = "\n<a href='https://t.me/$bot->username'>$bot->name</a>";
        $text = strlen($post->description) ?
            "$post->title\n$post->description\n$inviteLink" :
            "$post->title\n$inviteLink";
        if ($post->is_spoiler) {
            $text = "‼️ОБЕРЕЖНО, СПОЙЛЕР‼️\n<span class='tg-spoiler'>$text</span>";
        } elseif ($post->is_nsfw) {
            $text = "🔞ОБЕРЕЖНО, NSFW🔞\n<span class='tg-spoiler'>$text</span>";
        }
        $inputFile = InputFile::create($post->url, basename($post->url));
        $this->sendImagePost($inputFile, $text);

        // Логуємо результат роботи
        $end = Date::now();
        $executionTime = $end->diffInMilliseconds($start);

        $log = new Log();
        $log->subscriber_id = Subscriber::where('telegram_id', $chatId)->first()->id;
        $log->chat_type = 'private';
        $log->command = $this->name;
        $log->message = '';
        $log->send_time_taken = $executionTime;
        $log->save();
    }

    /**
     * @param InputFile $inputFile
     * @param string $text
     * @return void
     */
    public function sendImagePost(InputFile $inputFile, string $text): void
    {
        $this->replyWithChatAction([
            'action' => Actions::UPLOAD_PHOTO
        ]);

        $this->replyWithPhoto([
            'photo' => $inputFile,
            'caption' => $text,
            'parse_mode' => 'html',
        ]);
    }
}

<?php

namespace App\Commands;

use App\Models\Bot;
use App\Models\Log;
use App\Models\Post;
use App\Models\Subscriber;
use App\Services\Post\PostService;
use App\Services\Post\PostTypeEnum;
use Illuminate\Support\Facades\Date;
use Telegram\Bot\Actions;
use Telegram\Bot\Commands\Command;
use Telegram\Bot\Exceptions\TelegramSDKException;
use Telegram\Bot\FileUpload\InputFile;

class GetCommand extends Command
{
    protected string $name = 'get';
    protected string $pattern = '{type}';
    protected string $description = 'надсилає рандомний пост, або за типом /get <prompt> (image, gif, video, youtube, text) / sends a random post, or by type /get <prompt> (image, gif, video, youtube, text)';

    /**
     * @throws TelegramSDKException
     */
    public function handle(): void
    {
        // Засікаємо початковий час виконання запиту
        $start = Date::now();

        // отримуємо всі необхідні дані про запит
        $username = $this->telegram->getMe()->username;
        $bot = Bot::findByUsername($username);
        $type = $this->argument('type');
        $chatId = (string)$this->getUpdate()->getChat()->id;

        // чи присутній тип в команді користувача
        $typeIsContain = collect(PostTypeEnum::getValues())->contains($type);
        if (!$typeIsContain) {
            // отримуємо рандомний пост
            $post = PostService::getRandomPostFromBot($bot);
            // ініціалізуємо тип, адже його не задали
            $type = $post->type;
        } else {
            // отримуємо рандомний пост по типу
            $post = PostService::getRandomPostFromBotByType($bot, PostTypeEnum::from($type));
        }

        // якщо пост не получилось отримати
        if (is_null($post)) {
            $this->replyWithMessage([
                'text' => 'Нажаль, нам не вдалось знайти контент😞, спробуйте іншу команду - /help!'
            ]);
            return;
        }

        // Формуємо текст та файл для відповіді
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

        // В залежності від типу, викликаємо відповідні методи Telegram API
        switch ($type) {
            case PostTypeEnum::IMAGE->value:
                $this->sendImagePost($inputFile, $text);
                break;
            case PostTypeEnum::GIF->value:
                $this->sendGifPost($chatId, $inputFile, $text);
                break;
            case PostTypeEnum::VIDEO->value:
                $this->sendVideoPost($inputFile, $text, $post);
                break;
            case PostTypeEnum::YOUTUBE->value:
                $this->sendYoutubePost($text, $post);
                break;
            case PostTypeEnum::TEXT->value:
                $this->sendTextPost($text);
                break;
            default:
                $this->replyWithMessage([
                    'text' => 'Невідома помилка :(, спробуйте іншу команду - /help!'
                ]);
                break;
        }

        // Логуємо результат роботи
        $end = Date::now();
        $executionTime = $end->diffInMilliseconds($start);

        $log = new Log();
        $log->subscriber_id = Subscriber::where('telegram_id', $chatId)->first()->id;
        $log->chat_type = 'private';
        $log->command = $this->name;
        $log->message = $type;
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

    /**
     * @param mixed $chatId
     * @param InputFile $inputFile
     * @param string $text
     * @return void
     * @throws \Telegram\Bot\Exceptions\TelegramSDKException
     */
    public function sendGifPost(mixed $chatId, InputFile $inputFile, string $text): void
    {
        $this->replyWithChatAction([
            'action' => Actions::UPLOAD_DOCUMENT
        ]);

        $this->telegram->sendAnimation([
            'chat_id' => $chatId,
            'animation' => $inputFile,
            'caption' => $text,
            'parse_mode' => 'html',
        ]);
    }

    /**
     * @param InputFile $inputFile
     * @param string $text
     * @param \App\Models\Post $post
     * @return void
     */
    public function sendVideoPost(InputFile $inputFile, string $text, \App\Models\Post $post): void
    {
        $this->replyWithChatAction([
            'action' => Actions::UPLOAD_VIDEO
        ]);

        $this->replyWithVideo([
            'video' => $inputFile,
            'caption' => $text,
            'width' => $post->width,
            'height' => $post->height,
            'parse_mode' => 'html',
        ]);
    }

    /**
     * @param string $text
     * @param Post $post
     * @return void
     */
    public function sendYoutubePost(string $text, Post $post): void
    {
        $this->sendTextPost($text . "[­]($post->url)");
    }

    /**
     * @param string $text
     * @return void
     */
    public function sendTextPost(string $text): void
    {
        $this->replyWithChatAction([
            'action' => Actions::TYPING
        ]);

        $this->replyWithMessage([
            'text' => $text,
            'parse_mode' => 'html',
        ]);
    }
}

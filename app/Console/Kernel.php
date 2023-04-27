<?php

namespace App\Console;

use App\Models\PostSchedule;
use App\Services\Post\PostTypeEnum;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Telegram;
use Telegram\Bot\FileUpload\InputFile;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     *
     * @param \Illuminate\Console\Scheduling\Schedule $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // $schedule->command('inspire')->hourly();


        $schedule->call(function () {
            $postSchedule = PostSchedule::whereBetween('created_at', [now()->subMinutes(30), now()])->orderBy(
                'created_at',
                'DESC'
            )->first();
            if ($postSchedule) {
                $post = $postSchedule->post;
                $channel = $postSchedule->channel;
                $chatId = $postSchedule->channel->telegram_channel_id;

                $telegram = Telegram::bot($postSchedule->channel->bot->username);

                $type = $post->type;
                // Формуємо текст та файл для відповіді
                $inviteLink = "\n<a href='$channel->invite_link'>$channel->title</a>";
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
                        $telegram->sendPhoto([
                            'chat_id' => $chatId,
                            'photo' => $inputFile,
                            'caption' => $text,
                            'parse_mode' => 'html',
                        ]);
                        break;
                    case PostTypeEnum::GIF->value:
                        $telegram->telegram->sendAnimation([
                            'chat_id' => $chatId,
                            'animation' => $inputFile,
                            'caption' => $text,
                            'parse_mode' => 'html',
                        ]);
                        break;
                    case PostTypeEnum::VIDEO->value:
                        $telegram->sendVideo([
                            'chat_id' => $chatId,
                            'video' => $inputFile,
                            'caption' => $text,
                            'width' => $post->width,
                            'height' => $post->height,
                            'parse_mode' => 'html',
                        ]);
                        break;
                    case PostTypeEnum::YOUTUBE->value:
                        $telegram->sendMessage([
                            'chat_id' => $chatId,
                            'text' => $text . "<a href='$post->url'>[­]</a>",
                            'parse_mode' => 'html',
                        ]);
                        break;
                    case PostTypeEnum::TEXT->value:
                        $telegram->sendMessage([
                            'chat_id' => $chatId,
                            'text' => $text,
                            'parse_mode' => 'html',
                        ]);
                        break;
                    default:

                        break;
                }
            }
        })->everyMinute();
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__ . '/Commands');

        require base_path('routes/console.php');
    }
}

<?php

namespace App\Jobs;

use App\Models\Subscriber;
use App\Services\Post\PostTypeEnum;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Telegram\Bot\Api;
use Telegram\Bot\FileUpload\InputFile;

class TelegramSendMessageJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(
        private PostTypeEnum $postTypeEnum,
        private Api $telegram,
        private Subscriber $subscriber,
        private array $attachments
    ) {
        //
    }

    public function handle()
    {
        ['body' => $body, 'filePath' => $filePath] = $this->attachments;
        /*        $absoluteFilePath = Storage::path(basename($filePath));
                $contents = Storage::has($absoluteFilePath);
                Log::info($contents . '_' . $absoluteFilePath);
                dd($contents);*/
        $file = !is_null($filePath) ? InputFile::create($filePath, basename($filePath)) : null;

        switch ($this->postTypeEnum) {
            case PostTypeEnum::TEXT:
                $this->telegram->sendMessage([
                    'chat_id' => $this->subscriber->telegram_id,
                    'text' => $body,
                    'parse_mode' => 'Markdown'
                ]);
                break;
            case PostTypeEnum::IMAGE:
                $this->telegram->sendPhoto([
                    'chat_id' => $this->subscriber->telegram_id,
                    'photo' => $file,
                    'caption' => $body,
                    'parse_mode' => 'Markdown'
                ]);
                break;
            case PostTypeEnum::GIF:
                $this->telegram->sendAnimation([
                    'chat_id' => $this->subscriber->telegram_id,
                    'animation' => $file,
                    'caption' => $body,
                    'parse_mode' => 'Markdown'
                ]);
                break;
            case PostTypeEnum::VIDEO:
                $this->telegram->sendVideo([
                    'chat_id' => $this->subscriber->telegram_id,
                    'video' => $file,
                    'caption' => $body,
                    'parse_mode' => 'Markdown'
                ]);
                break;
        }
    }
}

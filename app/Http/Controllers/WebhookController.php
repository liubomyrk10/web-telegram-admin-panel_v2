<?php

namespace App\Http\Controllers;

use App\Models\Bot;
use Illuminate\Http\Request;
use Telegram\Bot\BotsManager;
use Telegram\Bot\Exceptions\TelegramSDKException;

class WebhookController extends Controller
{
    public function __construct(private BotsManager $telegram)
    {
        $this->setWebHooksForAllBots();
    }

    /**
     * @throws TelegramSDKException
     */
    public function __invoke(Request $request)
    {
        $token = $request['token'];
        $bot = Bot::findByToken($token);
        $api = $this->telegram->bot($bot->username);
        $webhook = $api->commandsHandler(true);

        return response(null, 200);
    }
    /*
     [ // app\Http\Controllers\WebhookController.php:26
"bots" => array:1 [
"mybot" => array:4 [
  "token" => "YOUR-BOT-TOKEN"
  "certificate_path" => "YOUR-CERTIFICATE-PATH"
  "webhook_url" => "YOUR-BOT-WEBHOOK-URL"
  "commands" => []
]
]
"default" => "mybot"
"async_requests" => false
"http_client_handler" => null
"base_bot_url" => null
"resolve_command_dependencies" => true
"commands" => array:1 [
0 => "Telegram\Bot\Commands\HelpCommand"
]
"command_groups" => []
"shared_commands" => []
]
     */

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

<?php

namespace App\Providers;

use App\Models\Bot;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $bots = Bot::all();

        foreach ($bots as $bot) {
            //$route = URL::route('webhook.settings', ['token' => $bot->token]);
            $route = "https://tgbot-admin.s-host.net/$bot->token/webhook";
            //$route = env('NGROK') . "/$bot->token/webhook";

            Config::set("telegram.bots.{$bot->username}.token", $bot->token);
            Config::set("telegram.bots.{$bot->username}.webhook_url", $route);
        }
    }
}

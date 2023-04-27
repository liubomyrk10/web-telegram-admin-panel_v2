<?php

namespace App\Services\Post;

use App\Models\Bot;
use App\Models\Post;

class PostService
{
    public static function getRandomPostFromBotByType(Bot $bot, PostTypeEnum $type): ?Post
    {
        $model = $bot->posts()->where('type', $type->value)->inRandomOrder()->first();
        return is_null($model) ? null : new Post($model->toArray());
    }

    public static function getRandomPostFromBot(Bot $bot): ?Post
    {
        $model = $bot->posts()->inRandomOrder()->first();
        return is_null($model) ? null : new Post($model->toArray());
    }

    public static function searchOnePostByTitle(Bot $bot, string $search): ?Post
    {
        $model = $bot->posts()->where('title', $search)->inRandomOrder()->first();
        return is_null($model) ? null : new Post($model->toArray());
    }
}

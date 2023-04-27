<?php

namespace App\Services\Post;

enum PostTypeEnum: string
{
    case IMAGE = 'image';
    case GIF = 'gif';
    case VIDEO = 'video';
    case YOUTUBE = 'youtube';
    case TEXT = 'text';

    public static function getValues(): array
    {
        return [
            self::IMAGE->value,
            self::GIF->value,
            self::VIDEO->value,
            self::YOUTUBE->value,
            self::TEXT->value,
        ];
    }
}

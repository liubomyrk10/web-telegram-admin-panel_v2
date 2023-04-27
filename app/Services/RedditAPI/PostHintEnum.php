<?php

namespace App\Services\RedditAPI;

enum PostHintEnum: string
{
    case IMAGE = 'image';
    case HOSTED_VIDEO = 'hosted:video';
    case RICH_VIDEO = 'rich:video';
    case LINK = 'link';
    case TEXT = 'self';
}

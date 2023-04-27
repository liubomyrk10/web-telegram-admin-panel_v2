<?php

namespace App\Services\RedditAPI;

use Carbon\Carbon;

class RedditPostDto
{
    public function __construct(
        public readonly string $id,
        public readonly string $subreddit,
        public readonly string $title,
        public readonly string $description,
        public readonly string $thumbnail,
        public readonly string $postHint,
        public readonly string $urlOverriddenByDest,
        public readonly bool $over18,
        public readonly bool $spoiler,
        public readonly bool $isVideo,
        public readonly array $preview,
        public readonly string $permalink,
        public readonly string $url,
        public readonly int $width,
        public readonly int $height,
        public readonly Carbon $created,
    ) {
    }
}

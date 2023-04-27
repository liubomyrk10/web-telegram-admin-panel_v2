<?php

namespace App\Services\RedditAPI;

use App\Models\Bot;
use App\Models\Post;
use App\Models\Subreddit;
use Illuminate\Support\Arr;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;
use RedditAPI;

class RedditPostService
{
    public static function getFilteredPosts(int $subredditId, string $type, string $parseType, int $limit = 25): array
    {
        return match ($parseType) {
            'new' => self::getFilteredNew($subredditId, $type, $limit),
            'top' => self::getFilteredTop($subredditId, $type, $limit),
            'hot' => self::getFilteredHot($subredditId, $type, $limit),
            'controversial' => self::getFilteredControversial($subredditId, $type, $limit),
        };
    }

    /*
     * Отримати всі Top пости, відфільтровані по типу та субреддіту.
     */
    public static function getFilteredTop(int $subredditId, string $type, int $limit = 25): array
    {
        $subredditName = Subreddit::find($subredditId)->name;
        return self::getPostsByType($type, RedditAPI::getTop(subreddit: $subredditName, limit: $limit));
    }

    /*
     * Отримати всі Hot пости, відфільтровані по типу та субреддіту.
     */
    public static function getFilteredHot(int $subredditId, string $type, int $limit = 25): array
    {
        $subredditName = Subreddit::find($subredditId)->name;
        return self::getPostsByType($type, RedditAPI::getHot(subreddit: $subredditName, limit: $limit));
    }

    /*
 * Отримати всі New пости, відфільтровані по типу та субреддіту.
 */
    public static function getFilteredNew(int $subredditId, string $type, int $limit = 25): array
    {
        $subredditName = Subreddit::find($subredditId)->name;
        return self::getPostsByType($type, RedditAPI::getNew(subreddit: $subredditName, limit: $limit));
    }

    /*
     * Отримати всі Controversial пости, відфільтровані по типу та субреддіту.
     */
    public static function getFilteredControversial(int $subredditId, string $type, int $limit = 25): array
    {
        $subredditName = Subreddit::find($subredditId)->name;
        return self::getPostsByType($type, RedditAPI::getControversial(subreddit: $subredditName, limit: $limit));
    }

    /**
     * Отримати всі пости, відфільтровані по типу.
     */
    private static function getPostsByType(string $type, $response): array
    {
        $filteredPosts = array_filter($response['data']['children'], function ($post) use ($type) {
            if (!Arr::has($post, 'data.post_hint')) {
                if (Arr::has($post, 'data.media.type')) {
                    if ($post['data']['media']['type'] == 'youtube.com'
                        && $type == 'youtube') {
                        return true;
                    }
                } elseif ($type == 'text') {
                    return true;
                }
                return false;
            }

            $postHint = $post['data']['post_hint'];

            if ($postHint == 'image' && $type == 'image') {
                foreach (['jpg', 'png', 'bmp', 'webp'] as $ext) {
                    if (Str::contains($post['data']['url'], $ext)) {
                        return true;
                    }
                }
            } elseif ($type == 'gif' && Str::endsWith($post['data']['url'], $type)) {
                return true;
            } elseif ($post['data']['is_video'] && Str::contains($postHint, $type) && $type == 'video') {
                return true;
            } elseif ($post['data']['is_self'] && $type == 'text') {
                return true;
            }
            /*            foreach (self::mapTypeToFileExtentions($type) as $extention) {
                            if (Str::contains($targetString, $extention)) {
                                echo "The string '{$string}' is present in the target string.\n";
                            }
                        }

                        return Str::contains($postHint, self::mapTypeToPostHint($type))
                            || ($postHint == 'link' && $type == 'video'
                                && Arr::has($post, 'data.crosspost_parent_list'))
                            || Str::endsWith($post['data']['url'], $type); // or is gif*/
        });

        return self::mapPostsToDto(array_values($filteredPosts));
    }

    public static function getPostsBySearch(string $search, Bot $bot): ?Post
    {
        $response = RedditAPI::search(
            query: $search,
            subreddit: $bot->subreddits()->first()->name,
            sort: 'hot',
            limit: 100
        );
        if (!Arr::whereNotNull($response)) {
            $response = RedditAPI::search(
                query: $search,
                subreddit: $bot->subreddits()->first()->name,
                sort: 'new',
                limit: 100
            );
        }

        if (!Arr::whereNotNull($response)) {
            $response = RedditAPI::search(
                query: $search,
                subreddit: $bot->subreddits()->first()->name,
                sort: 'top',
                limit: 100
            );
        }

        if (!Arr::whereNotNull($response)) {
            return null;
        }


        $type = '';
        $post = Arr::random($response['data']['children']);
        if (!Arr::has($post, 'data.post_hint')) {
            if (Arr::has($post, 'data.media.type')) {
                if ($post['data']['media']['type'] == 'youtube.com') {
                    $type = 'youtube';
                }
            }
        }

        $postHint = $post['data']['post_hint'];

        if ($postHint == 'image') {
            foreach (['jpg', 'png', 'bmp', 'webp'] as $ext) {
                if (Str::contains($post['data']['url'], $ext)) {
                    $type = 'image';
                }
            }
        } elseif ($type == 'gif' && Str::endsWith($post['data']['url'], $type)) {
            $type = 'gif';
        } elseif ($post['data']['is_video'] && Str::contains($postHint, $type) && $type == 'video') {
            $type = 'video';
        } elseif ($post['data']['is_self'] && $type == 'text') {
            $type = 'text';
        }

        return !empty($type) ? self::mapDtoToPost($post, $type) : null;
    }


    private static function mapPostsToDto(array $posts): array
    {
        return array_map(function ($post) {
            $postHint = $post['data']['post_hint'] ?? '';
            return new RedditPostDto(
                id: $post['data']['id'],
                subreddit: $post['data']['subreddit'],
                title: $post['data']['title'],
                description: $post['data']['selftext'] ?? '',
                thumbnail: $post['data']['thumbnail'] ?? '',
                postHint: $postHint,
                urlOverriddenByDest: $post['data']['url_overridden_by_dest'] ?? '',
                over18: $post['data']['over_18'] ?? false,
                spoiler: $post['data']['spoiler'] ?? false,
                isVideo: $post['data']['is_video'] ?? false,
                preview: $post['data']['preview']['images'][0]['source'] ?? [],
                permalink: "https://www.reddit.com{$post['data']['permalink']}",
                url: self::getPostUrl($post, $postHint),
                width: $post['data']['preview']['images'][0]['source']['width'] ?? 0,
                height: $post['data']['preview']['images'][0]['source']['height'] ?? 0,
                created: Carbon::createFromTimestamp($post['data']['created_utc'])
            );
        }, $posts);
    }

    private static function mapDtoToPost(array $post, string $type): Post
    {
        $postHint = $post['data']['post_hint'] ?? '';
        $newPost = new Post();
        $newPost->reddit_id = $post['data']['id'];
        $newPost->subreddit_id = Subreddit::whereName($post['data']['subreddit'])->firstOrFail()->id;
        $newPost->type = $type;
        $newPost->title = $post['data']['title'];
        $newPost->description = $post['data']['selftext'] ?? '';
        $newPost->is_nsfw = $post['data']['over_18'] ?? false;
        $newPost->is_spoiler = $post['data']['spoiler'] ?? false;
        $newPost->permalink = "https://www.reddit.com{$post['data']['permalink']}";
        $newPost->url = self::getPostUrl($post, $postHint);
        $newPost->width = $post['data']['preview']['images'][0]['source']['width'] ?? 0;
        $newPost->height = $post['data']['preview']['images'][0]['source']['height'] ?? 0;
        return $newPost;
    }

    private static function getPostUrl(array $post, string $postHint): string
    {
        $url = "";
        $fallback = "?source=fallback";
        $post = $post['data'];
        if ($postHint == PostHintEnum::IMAGE->value) {
            $url = $post['url'];
        } elseif ($postHint == PostHintEnum::HOSTED_VIDEO->value) {
            $url = rtrim($post['media']['reddit_video']['fallback_url'], $fallback);
        } elseif ($postHint == PostHintEnum::RICH_VIDEO->value && Arr::has($post, 'preview.reddit_video_preview')) {
            $url = rtrim($post['preview']['reddit_video_preview']['fallback_url'], $fallback);
        } elseif ($postHint == PostHintEnum::LINK->value && Arr::has($post, 'crosspost_parent_list')) {
            $crossPost = $post['crosspost_parent_list'][0];
            if (isset($crossPost['secure_media'])) {
                $url = rtrim($crossPost['secure_media']['reddit_video']['fallback_url'], $fallback);
            }
        } elseif (Arr::has($post, 'media.type')) {
            if ($post['media']['type'] == 'youtube.com') {
                $url = $post['url'];
            }
        } else {
            $url = $post['url'];
        }

        return trim($url);
    }

    private
    static function mapTypeToPostHint(
        string $type
    ): string {
        return match ($type) {
            'text' => 'self',
            default => $type
        };
    }

    private
    static function mapTypeToFileExtentions(
        string $type
    ): array {
        return match ($type) {
            'image' => ['jpg', 'png', 'bmp', 'webp'],
            'gif' => ['gif', 'apng'],
            'video' => ['mp4', 'mov'],
            default => $type
        };
    }
}

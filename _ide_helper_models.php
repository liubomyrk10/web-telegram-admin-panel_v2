<?php

// @formatter:off
/**
 * A helper file for your Eloquent Models
 * Copy the phpDocs from this file to the correct Model,
 * And remove them from this file, to prevent double declarations.
 *
 * @author Barry vd. Heuvel <barryvdh@gmail.com>
 */


namespace App\Models{
/**
 * App\Models\Bot
 *
 * @property int $id
 * @property int $telegram_id
 * @property int $user_id
 * @property string $username
 * @property string $name
 * @property string $about
 * @property string $description
 * @property string $avatar
 * @property string $token
 * @property int $is_active
 * @property string|null $welcome_message_text
 * @property string|null $help_message_text
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Channel> $channels
 * @property-read int|null $channels_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Post> $posts
 * @property-read int|null $posts_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Post> $pots
 * @property-read int|null $pots_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Subreddit> $subreddits
 * @property-read int|null $subreddits_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Subscriber> $subscribers
 * @property-read int|null $subscribers_count
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder|Bot countByOwner()
 * @method static \Illuminate\Database\Eloquent\Builder|Bot defaultSort(string $column, string $direction = 'asc')
 * @method static \Illuminate\Database\Eloquent\Builder|Bot filters(?mixed $kit = null, ?\Orchid\Filters\HttpFilter $httpFilter = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Bot filtersApply(iterable $filters = [])
 * @method static \Illuminate\Database\Eloquent\Builder|Bot filtersApplySelection($class)
 * @method static \Illuminate\Database\Eloquent\Builder|Bot findByToken(string $token)
 * @method static \Illuminate\Database\Eloquent\Builder|Bot findByUsername(string $username)
 * @method static \Illuminate\Database\Eloquent\Builder|Bot newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Bot newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Bot onlyOwner()
 * @method static \Illuminate\Database\Eloquent\Builder|Bot onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Bot query()
 * @method static \Illuminate\Database\Eloquent\Builder|Bot whereAbout($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Bot whereAvatar($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Bot whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Bot whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Bot whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Bot whereHelpMessageText($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Bot whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Bot whereIsActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Bot whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Bot whereTelegramId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Bot whereToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Bot whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Bot whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Bot whereUsername($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Bot whereWelcomeMessageText($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Bot withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Bot withoutTrashed()
 */
	class Bot extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Channel
 *
 * @property int $id
 * @property int $telegram_id
 * @property int $bot_id
 * @property string $title
 * @property string|null $username
 * @property string|null $description
 * @property string|null $photo
 * @property int|null $member_count
 * @property int $is_public
 * @property string|null $invite_link
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Bot $bot
 * @property-read string $telegram_channel_id
 * @method static \Illuminate\Database\Eloquent\Builder|Channel countByOwner()
 * @method static \Illuminate\Database\Eloquent\Builder|Channel defaultSort(string $column, string $direction = 'asc')
 * @method static \Illuminate\Database\Eloquent\Builder|Channel filters(?mixed $kit = null, ?\Orchid\Filters\HttpFilter $httpFilter = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Channel filtersApply(iterable $filters = [])
 * @method static \Illuminate\Database\Eloquent\Builder|Channel filtersApplySelection($class)
 * @method static \Illuminate\Database\Eloquent\Builder|Channel newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Channel newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Channel onlyOwner()
 * @method static \Illuminate\Database\Eloquent\Builder|Channel query()
 * @method static \Illuminate\Database\Eloquent\Builder|Channel whereBotId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Channel whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Channel whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Channel whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Channel whereInviteLink($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Channel whereIsPublic($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Channel whereMemberCount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Channel wherePhoto($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Channel whereTelegramId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Channel whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Channel whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Channel whereUsername($value)
 */
	class Channel extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Log
 *
 * @property int $id
 * @property int $subscriber_id
 * @property string $chat_type
 * @property string|null $command
 * @property string|null $message
 * @property string $send_time_taken
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read string $send_time_taken_in_seconds
 * @property-read \App\Models\Subscriber $subscriber
 * @method static \Illuminate\Database\Eloquent\Builder|Log averageByDays(string $value, $startDate = null, $stopDate = null, ?string $dateColumn = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Log countByDays($startDate = null, $stopDate = null, ?string $dateColumn = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Log countByOwner()
 * @method static \Illuminate\Database\Eloquent\Builder|Log countForGroup(string $groupColumn)
 * @method static \Illuminate\Database\Eloquent\Builder|Log defaultSort(string $column, string $direction = 'asc')
 * @method static \Illuminate\Database\Eloquent\Builder|Log filters(?mixed $kit = null, ?\Orchid\Filters\HttpFilter $httpFilter = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Log filtersApply(iterable $filters = [])
 * @method static \Illuminate\Database\Eloquent\Builder|Log filtersApplySelection($class)
 * @method static \Illuminate\Database\Eloquent\Builder|Log maxByDays(string $value, $startDate = null, $stopDate = null, ?string $dateColumn = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Log minByDays(string $value, $startDate = null, $stopDate = null, ?string $dateColumn = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Log newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Log newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Log onlyOwner()
 * @method static \Illuminate\Database\Eloquent\Builder|Log query()
 * @method static \Illuminate\Database\Eloquent\Builder|Log sumByDays(string $value, $startDate = null, $stopDate = null, ?string $dateColumn = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Log valuesByDays(string $value, $startDate = null, $stopDate = null, string $dateColumn = 'created_at')
 * @method static \Illuminate\Database\Eloquent\Builder|Log weakCountByOwner()
 * @method static \Illuminate\Database\Eloquent\Builder|Log whereChatType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Log whereCommand($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Log whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Log whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Log whereMessage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Log whereSendTimeTaken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Log whereSubscriberId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Log whereUpdatedAt($value)
 */
	class Log extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Post
 *
 * @property int $id
 * @property string $reddit_id
 * @property int $subreddit_id
 * @property string $type
 * @property string|null $title
 * @property string|null $description
 * @property string|null $url
 * @property string|null $permalink
 * @property int|null $width
 * @property int|null $height
 * @property int $is_nsfw
 * @property int $is_spoiler
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Bot> $bots
 * @property-read int|null $bots_count
 * @property-read \App\Models\Subreddit $subreddit
 * @method static \Illuminate\Database\Eloquent\Builder|Post countByOwner()
 * @method static \Illuminate\Database\Eloquent\Builder|Post defaultSort(string $column, string $direction = 'asc')
 * @method static \Illuminate\Database\Eloquent\Builder|Post filters(?mixed $kit = null, ?\Orchid\Filters\HttpFilter $httpFilter = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Post filtersApply(iterable $filters = [])
 * @method static \Illuminate\Database\Eloquent\Builder|Post filtersApplySelection($class)
 * @method static \Illuminate\Database\Eloquent\Builder|Post newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Post newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Post onlyOwner()
 * @method static \Illuminate\Database\Eloquent\Builder|Post onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Post query()
 * @method static \Illuminate\Database\Eloquent\Builder|Post weakCountByOwner()
 * @method static \Illuminate\Database\Eloquent\Builder|Post whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Post whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Post whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Post whereHeight($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Post whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Post whereIsNsfw($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Post whereIsSpoiler($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Post wherePermalink($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Post whereRedditId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Post whereSubredditId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Post whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Post whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Post whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Post whereUrl($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Post whereWidth($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Post withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Post withoutTrashed()
 */
	class Post extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\PostSchedule
 *
 * @property int $id
 * @property int $channel_id
 * @property int $post_id
 * @property \Illuminate\Support\Carbon $post_time
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Channel $channel
 * @property-read \App\Models\Post $post
 * @method static \Illuminate\Database\Eloquent\Builder|PostSchedule countByOwner()
 * @method static \Illuminate\Database\Eloquent\Builder|PostSchedule defaultSort(string $column, string $direction = 'asc')
 * @method static \Illuminate\Database\Eloquent\Builder|PostSchedule filters(?mixed $kit = null, ?\Orchid\Filters\HttpFilter $httpFilter = null)
 * @method static \Illuminate\Database\Eloquent\Builder|PostSchedule filtersApply(iterable $filters = [])
 * @method static \Illuminate\Database\Eloquent\Builder|PostSchedule filtersApplySelection($class)
 * @method static \Illuminate\Database\Eloquent\Builder|PostSchedule newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PostSchedule newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PostSchedule postedCountByOwner()
 * @method static \Illuminate\Database\Eloquent\Builder|PostSchedule query()
 * @method static \Illuminate\Database\Eloquent\Builder|PostSchedule whereChannelId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PostSchedule whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PostSchedule whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PostSchedule wherePostId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PostSchedule wherePostTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PostSchedule whereUpdatedAt($value)
 */
	class PostSchedule extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Subreddit
 *
 * @property int $id
 * @property string $name
 * @property string|null $description
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Bot> $bots
 * @property-read int|null $bots_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Post> $posts
 * @property-read int|null $posts_count
 * @method static \Illuminate\Database\Eloquent\Builder|Subreddit defaultSort(string $column, string $direction = 'asc')
 * @method static \Illuminate\Database\Eloquent\Builder|Subreddit filters(?mixed $kit = null, ?\Orchid\Filters\HttpFilter $httpFilter = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Subreddit filtersApply(iterable $filters = [])
 * @method static \Illuminate\Database\Eloquent\Builder|Subreddit filtersApplySelection($class)
 * @method static \Illuminate\Database\Eloquent\Builder|Subreddit newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Subreddit newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Subreddit query()
 * @method static \Illuminate\Database\Eloquent\Builder|Subreddit whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Subreddit whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Subreddit whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Subreddit whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Subreddit whereUpdatedAt($value)
 */
	class Subreddit extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Subscriber
 *
 * @property int $id
 * @property int $telegram_id
 * @property int $bot_id
 * @property string $first_name
 * @property string|null $last_name
 * @property string|null $username
 * @property string|null $lang
 * @property int $is_blocked
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Bot $bot
 * @property-read string $full_name
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Log> $logs
 * @property-read int|null $logs_count
 * @method static \Illuminate\Database\Eloquent\Builder|Subscriber averageByDays(string $value, $startDate = null, $stopDate = null, ?string $dateColumn = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Subscriber countByDays($startDate = null, $stopDate = null, ?string $dateColumn = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Subscriber countByOwner()
 * @method static \Illuminate\Database\Eloquent\Builder|Subscriber countForGroup(string $groupColumn)
 * @method static \Illuminate\Database\Eloquent\Builder|Subscriber defaultSort(string $column, string $direction = 'asc')
 * @method static \Illuminate\Database\Eloquent\Builder|Subscriber filters(?mixed $kit = null, ?\Orchid\Filters\HttpFilter $httpFilter = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Subscriber filtersApply(iterable $filters = [])
 * @method static \Illuminate\Database\Eloquent\Builder|Subscriber filtersApplySelection($class)
 * @method static \Illuminate\Database\Eloquent\Builder|Subscriber maxByDays(string $value, $startDate = null, $stopDate = null, ?string $dateColumn = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Subscriber minByDays(string $value, $startDate = null, $stopDate = null, ?string $dateColumn = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Subscriber newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Subscriber newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Subscriber onlyOwner()
 * @method static \Illuminate\Database\Eloquent\Builder|Subscriber query()
 * @method static \Illuminate\Database\Eloquent\Builder|Subscriber sumByDays(string $value, $startDate = null, $stopDate = null, ?string $dateColumn = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Subscriber valuesByDays(string $value, $startDate = null, $stopDate = null, string $dateColumn = 'created_at')
 * @method static \Illuminate\Database\Eloquent\Builder|Subscriber weakCountByOwner()
 * @method static \Illuminate\Database\Eloquent\Builder|Subscriber whereBotId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Subscriber whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Subscriber whereFirstName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Subscriber whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Subscriber whereIsBlocked($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Subscriber whereLang($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Subscriber whereLastName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Subscriber whereTelegramId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Subscriber whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Subscriber whereUsername($value)
 */
	class Subscriber extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\User
 *
 * @property int $id
 * @property string $name
 * @property string $email
 * @property \Illuminate\Support\Carbon|null $email_verified_at
 * @property string $password
 * @property string|null $remember_token
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property array|null $permissions
 * @property int|null $telegram_id
 * @property string|null $first_name
 * @property string|null $last_name
 * @property string|null $username
 * @property string|null $photo_url
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection<int, \Illuminate\Notifications\DatabaseNotification> $notifications
 * @property-read int|null $notifications_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Orchid\Platform\Models\Role> $roles
 * @property-read int|null $roles_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Subscriber> $subscribers
 * @property-read int|null $subscribers_count
 * @method static \Illuminate\Database\Eloquent\Builder|User averageByDays(string $value, $startDate = null, $stopDate = null, ?string $dateColumn = null)
 * @method static \Illuminate\Database\Eloquent\Builder|User byAccess(string $permitWithoutWildcard)
 * @method static \Illuminate\Database\Eloquent\Builder|User byAnyAccess($permitsWithoutWildcard)
 * @method static \Illuminate\Database\Eloquent\Builder|User countByDays($startDate = null, $stopDate = null, ?string $dateColumn = null)
 * @method static \Illuminate\Database\Eloquent\Builder|User countForGroup(string $groupColumn)
 * @method static \Illuminate\Database\Eloquent\Builder|User defaultSort(string $column, string $direction = 'asc')
 * @method static \Database\Factories\UserFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder|User filters(?mixed $kit = null, ?\Orchid\Filters\HttpFilter $httpFilter = null)
 * @method static \Illuminate\Database\Eloquent\Builder|User filtersApply(iterable $filters = [])
 * @method static \Illuminate\Database\Eloquent\Builder|User filtersApplySelection($class)
 * @method static \Illuminate\Database\Eloquent\Builder|User maxByDays(string $value, $startDate = null, $stopDate = null, ?string $dateColumn = null)
 * @method static \Illuminate\Database\Eloquent\Builder|User minByDays(string $value, $startDate = null, $stopDate = null, ?string $dateColumn = null)
 * @method static \Illuminate\Database\Eloquent\Builder|User newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|User newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|User query()
 * @method static \Illuminate\Database\Eloquent\Builder|User sumByDays(string $value, $startDate = null, $stopDate = null, ?string $dateColumn = null)
 * @method static \Illuminate\Database\Eloquent\Builder|User valuesByDays(string $value, $startDate = null, $stopDate = null, string $dateColumn = 'created_at')
 * @method static \Illuminate\Database\Eloquent\Builder|User whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereEmailVerifiedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereFirstName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereLastName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User wherePermissions($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User wherePhotoUrl($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereTelegramId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereUsername($value)
 */
	class User extends \Eloquent {}
}


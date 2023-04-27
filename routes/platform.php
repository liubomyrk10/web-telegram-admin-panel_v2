<?php

declare(strict_types=1);

use App\Orchid\Screens\Bot\BotEditScreen;
use App\Orchid\Screens\Bot\BotListScreen;
use App\Orchid\Screens\Channel\ChannelEditScreen;
use App\Orchid\Screens\Channel\ChannelListScreen;
use App\Orchid\Screens\Examples\ExampleCardsScreen;
use App\Orchid\Screens\Examples\ExampleChartsScreen;
use App\Orchid\Screens\Examples\ExampleFieldsAdvancedScreen;
use App\Orchid\Screens\Examples\ExampleFieldsScreen;
use App\Orchid\Screens\Examples\ExampleLayoutsScreen;
use App\Orchid\Screens\Examples\ExampleScreen;
use App\Orchid\Screens\Examples\ExampleTextEditorsScreen;
use App\Orchid\Screens\Log\LogCardScreen;
use App\Orchid\Screens\Log\LogListScreen;
use App\Orchid\Screens\PlatformScreen;
use App\Orchid\Screens\Post\PostEditScreen;
use App\Orchid\Screens\Post\PostListScreen;
use App\Orchid\Screens\PostSchedule\PostScheduleCardScreen;
use App\Orchid\Screens\PostSchedule\PostScheduleEditScreen;
use App\Orchid\Screens\PostSchedule\PostScheduleListScreen;
use App\Orchid\Screens\Role\RoleEditScreen;
use App\Orchid\Screens\Role\RoleListScreen;
use App\Orchid\Screens\SendMessage\SendMessageScreen;
use App\Orchid\Screens\Subreddit\SubredditListScreen;
use App\Orchid\Screens\Subscriber\SubscriberCardScreen;
use App\Orchid\Screens\Subscriber\SubscriberListScreen;
use App\Orchid\Screens\User\UserEditScreen;
use App\Orchid\Screens\User\UserListScreen;
use App\Orchid\Screens\User\UserProfileScreen;
use Illuminate\Support\Facades\Route;
use Tabuna\Breadcrumbs\Trail;

/*
|--------------------------------------------------------------------------
| Dashboard Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the need "dashboard" middleware group. Now create something great!
|
*/

// Main
Route::screen('/main', PlatformScreen::class)
    ->name('platform.index');

// Platform > Субреддіти
Route::screen('/subreddits', SubredditListScreen::class)
    ->name('platform.subreddits')
    ->breadcrumbs(fn(Trail $trail) => $trail
        ->parent('platform.index')
        ->push(__('Субреддіти'), route('platform.subreddits')));


// Platform > System > Bots > Edit
Route::screen('bots/{bot}/edit', BotEditScreen::class)
    ->name('platform.bots.edit')
    ->breadcrumbs(fn(Trail $trail, $bot) => $trail
        ->parent('platform.bots')
        ->push($bot->name, route('platform.bots.edit', $bot)));

// Platform > System > Bots > Create
Route::screen('bots/create', BotEditScreen::class)
    ->name('platform.bots.create')
    ->breadcrumbs(fn(Trail $trail) => $trail
        ->parent('platform.bots')
        ->push(__('Create'), route('platform.bots.create')));

// Platform > System > Bots
Route::screen('bots', BotListScreen::class)
    ->name('platform.bots')
    ->breadcrumbs(fn(Trail $trail) => $trail
        ->parent('platform.index')
        ->push("Боти", route('platform.bots')));


// Platform > System > Subscribers > Card
Route::screen('subscribers/{subscriber}/card', SubscriberCardScreen::class)
    ->name('platform.subscribers.card')
    ->breadcrumbs(fn(Trail $trail, $subscriber) => $trail
        ->parent('platform.subscribers')
        ->push('Карточка', route('platform.subscribers.card', $subscriber)));

// Platform > System > Subscribers
Route::screen('subscribers', SubscriberListScreen::class)
    ->name('platform.subscribers')
    ->breadcrumbs(fn(Trail $trail) => $trail
        ->parent('platform.index')
        ->push("Підписники", route('platform.subscribers')));

// Platform > System > Posts > Edit
Route::screen('posts/{post}/edit', PostEditScreen::class)
    ->name('platform.posts.edit')
    ->breadcrumbs(fn(Trail $trail, $post) => $trail
        ->parent('platform.posts')
        ->push($post->title, route('platform.posts.edit', $post)));

// Platform > System > Posts > Create
Route::screen('posts/create', PostEditScreen::class)
    ->name('platform.posts.create')
    ->breadcrumbs(fn(Trail $trail) => $trail
        ->parent('platform.posts')
        ->push(__('Create'), route('platform.posts.create')));

// Platform > System > Posts
Route::screen('posts', PostListScreen::class)
    ->name('platform.posts')
    ->breadcrumbs(fn(Trail $trail) => $trail
        ->parent('platform.index')
        ->push("Пости", route('platform.posts')));

// Platform > System > PostsSchedule > Edit
Route::screen('posts_schedule/{post_schedule}/edit', PostScheduleEditScreen::class)
    ->name('platform.posts_schedule.edit')
    ->breadcrumbs(fn(Trail $trail, $postSchedule) => $trail
        ->parent('platform.posts_schedule')
        ->push('Редагування відкладеного поста', route('platform.posts_schedule.edit', $postSchedule)));

// Platform > System > PostsSchedule > Create
Route::screen('posts_schedule/create', PostScheduleEditScreen::class)
    ->name('platform.posts_schedule.create')
    ->breadcrumbs(fn(Trail $trail) => $trail
        ->parent('platform.posts_schedule')
        ->push(__('Create'), route('platform.posts_schedule.create')));

// Platform > System > PostsSchedule
Route::screen('posts_schedule', PostScheduleListScreen::class)
    ->name('platform.posts_schedule')
    ->breadcrumbs(fn(Trail $trail) => $trail
        ->parent('platform.index')
        ->push("Відкладені пости", route('platform.posts_schedule')));

// Platform > System > PostsSchedule > Card
Route::screen('posts_schedule/{post_schedule}/card', PostScheduleCardScreen::class)
    ->name('platform.posts_schedule.card')
    ->breadcrumbs(fn(Trail $trail, $postSchedule) => $trail
        ->parent('platform.posts_schedule')
        ->push('Карточка', route('platform.posts_schedule.card', $postSchedule)));


// Platform > System > Channels > Edit
Route::screen('channels/{channel}/edit', ChannelEditScreen::class)
    ->name('platform.channels.edit')
    ->breadcrumbs(fn(Trail $trail, $channel) => $trail
        ->parent('platform.channels')
        ->push($channel->title, route('platform.channels.edit', $channel)));

// Platform > System > Channels > Create
Route::screen('channels/create', ChannelEditScreen::class)
    ->name('platform.channels.create')
    ->breadcrumbs(fn(Trail $trail) => $trail
        ->parent('platform.channels')
        ->push(__('Create'), route('platform.channels.create')));

// Platform > System > Channels
Route::screen('channels', ChannelListScreen::class)
    ->name('platform.channels')
    ->breadcrumbs(fn(Trail $trail) => $trail
        ->parent('platform.index')
        ->push("Канали", route('platform.channels')));


// Platform > System > Logs
Route::screen('logs', LogListScreen::class)
    ->name('platform.logs')
    ->breadcrumbs(fn(Trail $trail) => $trail
        ->parent('platform.index')
        ->push("Логи", route('platform.logs')));

// Platform > System > Logs > Card
Route::screen('logs/{logs}/card', LogCardScreen::class)
    ->name('platform.logs.card')
    ->breadcrumbs(fn(Trail $trail, $log) => $trail
        ->parent('platform.logs')
        ->push('Карточка', route('platform.logs.card', $log)));


// Platform > Profile
Route::screen('profile', UserProfileScreen::class)
    ->name('platform.profile')
    ->breadcrumbs(fn(Trail $trail) => $trail
        ->parent('platform.index')
        ->push(__('Profile'), route('platform.profile')));

// Platform > System > Users > User
Route::screen('users/{user}/edit', UserEditScreen::class)
    ->name('platform.systems.users.edit')
    ->breadcrumbs(fn(Trail $trail, $user) => $trail
        ->parent('platform.systems.users')
        ->push($user->name, route('platform.systems.users.edit', $user)));

// Platform > System > Users > Create
Route::screen('users/create', UserEditScreen::class)
    ->name('platform.systems.users.create')
    ->breadcrumbs(fn(Trail $trail) => $trail
        ->parent('platform.systems.users')
        ->push(__('Create'), route('platform.systems.users.create')));

// Platform > System > Users
Route::screen('users', UserListScreen::class)
    ->name('platform.systems.users')
    ->breadcrumbs(fn(Trail $trail) => $trail
        ->parent('platform.index')
        ->push(__('Users'), route('platform.systems.users')));

// Platform > System > Roles > Role
Route::screen('roles/{role}/edit', RoleEditScreen::class)
    ->name('platform.systems.roles.edit')
    ->breadcrumbs(fn(Trail $trail, $role) => $trail
        ->parent('platform.systems.roles')
        ->push($role->name, route('platform.systems.roles.edit', $role)));

// Platform > System > Roles > Create
Route::screen('roles/create', RoleEditScreen::class)
    ->name('platform.systems.roles.create')
    ->breadcrumbs(fn(Trail $trail) => $trail
        ->parent('platform.systems.roles')
        ->push(__('Create'), route('platform.systems.roles.create')));

// Platform > System > Roles
Route::screen('roles', RoleListScreen::class)
    ->name('platform.systems.roles')
    ->breadcrumbs(fn(Trail $trail) => $trail
        ->parent('platform.index')
        ->push(__('Roles'), route('platform.systems.roles')));

// Platform > System > Розсилка
Route::screen('send-message', SendMessageScreen::class)
    ->name('platform.send_message')
    ->breadcrumbs(fn(Trail $trail) => $trail
        ->parent('platform.index')
        ->push("Розсилка", route('platform.send_message')));

// Example...
Route::screen('example', ExampleScreen::class)
    ->name('platform.example')
    ->breadcrumbs(fn(Trail $trail) => $trail
        ->parent('platform.index')
        ->push('Example screen'));

Route::screen('example-fields', ExampleFieldsScreen::class)->name('platform.example.fields');
Route::screen('example-layouts', ExampleLayoutsScreen::class)->name('platform.example.layouts');
Route::screen('example-charts', ExampleChartsScreen::class)->name('platform.example.charts');
Route::screen('example-editors', ExampleTextEditorsScreen::class)->name('platform.example.editors');
Route::screen('example-cards', ExampleCardsScreen::class)->name('platform.example.cards');
Route::screen('example-advanced', ExampleFieldsAdvancedScreen::class)->name('platform.example.advanced');

//Route::screen('idea', Idea::class, 'platform.screens.idea');

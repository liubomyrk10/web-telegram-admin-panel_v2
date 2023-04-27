<?php

namespace App\Orchid\Layouts\Bot;

use App\Models\Bot;
use Illuminate\Support\Str;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Actions\DropDown;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Fields\CheckBox;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Layouts\Persona;
use Orchid\Screen\Layouts\Table;
use Orchid\Screen\TD;

class BotListLayout extends Table
{
    /**
     * Data source.
     *
     * The name of the key to fetch it from the query.
     * The results of which will be elements of the table.
     *
     * @var string
     */
    protected $target = 'bots';

    /**
     * Get the table cells to be displayed.
     *
     * @return TD[]
     */
    protected function columns(): iterable
    {
        return [
            TD::make('name', __('Name'))
                ->sort()
                ->cantHide()
                ->filter(Input::make())
                ->render(fn(Bot $bot) => new Persona($bot->presenter())),

            TD::make('telegram_id', __('Telegram ID')),

            TD::make('username', __('Username'))->filter(Input::make())->sort(),

            TD::make('user_id', 'Власник')
                ->render(fn(Bot $bot) => $bot->user->name)
                ->defaultHidden(),

            TD::make('subreddits', 'Субреддіти')->render(function (Bot $bot) {
                $subreddits = $bot->subreddits();
                return $subreddits->pluck('name')->implode(', ');
            }),

            TD::make('about', 'Про бота')->render(
                fn(Bot $bot) => Str::limit($bot->about, 20)
            )->defaultHidden(),

            TD::make('description', 'Опис')->render(
                fn(Bot $bot) => Str::limit($bot->description, 50)
            )->defaultHidden(),

            TD::make('avatar', 'Аватар')->defaultHidden(),

            TD::make('token', 'HTTP API token')->defaultHidden(),

            TD::make('is_active', 'Активний')->render(function (Bot $bot) {
                return CheckBox::make('is_active')
                    ->checked($bot->is_active)
                    ->disabled();
            }),

            TD::make('updated_at', __('Last edit'))
                ->sort()
                ->render(fn(Bot $bot) => $bot->updated_at->toDateTimeString()),

            TD::make('Дії')
                ->align(TD::ALIGN_CENTER)
                ->width('100px')
                ->render(fn(Bot $bot) => DropDown::make()
                    ->icon('options-vertical')
                    ->list([

                        Link::make(__('Edit'))
                            ->route('platform.bots.edit', $bot->id)
                            ->icon('pencil'),

                        Button::make(__('Delete'))
                            ->icon('trash')
                            ->confirm(
                                'Якщо ви випадково видалите бота, але захочете його відновити - зверніться до супер адміністратора.'
                            )
                            ->method('remove', [
                                'id' => $bot->id,
                            ]),
                    ])),
        ];
    }
}

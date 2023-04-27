<?php

namespace App\Orchid\Layouts\Channel;

use App\Models\Channel;
use Illuminate\Support\Str;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Actions\DropDown;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Fields\CheckBox;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Layouts\Persona;
use Orchid\Screen\Layouts\Table;
use Orchid\Screen\TD;

class ChannelListLayout extends Table
{
    /**
     * Data source.
     *
     * The name of the key to fetch it from the query.
     * The results of which will be elements of the table.
     *
     * @var string
     */
    protected $target = 'channels';

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
                ->render(fn(Channel $channel) => new Persona($channel->presenter())),

            TD::make('telegram_id', __('Telegram ID')),

            TD::make('username', __('Username'))->filter(Input::make())->sort(),

            TD::make('title', 'Заголовок')->filter(Input::make())->sort(),

            TD::make('description', 'Опис')->render(
                fn(Channel $channel) => Str::limit($channel->description, 50)
            )->defaultHidden(),

            TD::make('photo', 'Фото')->defaultHidden(),

            TD::make('member_count', 'Підписників'),

            TD::make('is_public', 'Публічний')->render(function (Channel $channel) {
                return CheckBox::make('is_public')
                    ->value($channel->is_public)
                    ->disabled();
            }),

            TD::make('invite_link', 'Підписників')->render(function (Channel $channel) {
                return Link::make('Go')
                    ->href($channel->invite_link)
                    ->target('_blank');
            }),

            TD::make('created_at', 'Дата створення')
                ->sort()
                ->render(fn(Channel $channel) => $channel->created_at->toDateTimeString()),

            TD::make('Дії')
                ->align(TD::ALIGN_CENTER)
                ->width('100px')
                ->render(fn(Channel $channel) => DropDown::make()
                    ->icon('options-vertical')
                    ->list([

                        Link::make(__('Edit'))
                            ->route('platform.channels.edit', $channel->id)
                            ->icon('pencil'),

                        Button::make(__('Delete'))
                            ->icon('trash')
                            ->confirm(
                                'Функція видалення не зворотня. Ви впевнені?'
                            )
                            ->method('remove', [
                                'id' => $channel->id,
                            ]),
                    ])),
        ];
    }
}

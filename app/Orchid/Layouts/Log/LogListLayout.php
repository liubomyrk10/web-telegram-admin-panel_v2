<?php

namespace App\Orchid\Layouts\Log;

use App\Models\Log;
use Illuminate\Support\Str;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Layouts\Table;
use Orchid\Screen\TD;

class LogListLayout extends Table
{
    /**
     * Data source.
     *
     * The name of the key to fetch it from the query.
     * The results of which will be elements of the table.
     *
     * @var string
     */
    protected $target = 'logs';

    /**
     * Get the table cells to be displayed.
     *
     * @return TD[]
     */
    protected function columns(): iterable
    {
        return [
            TD::make('id', 'id'),

            TD::make('subscriber_id', 'Підписник')
                ->render(fn(Log $log) => Link::make($log->subscriber->username)
                    ->route('platform.subscribers.card', $log->subscriber->id)),

            TD::make('chat_type', 'Тип чату'),

            TD::make('command', 'Команда'),

            TD::make('message', 'Повідомлення')->render(
                fn(Log $log) => Str::limit($log->message, 50)
            )->defaultHidden(),

            TD::make('send_time_taken_in_seconds', 'Витрачено часу (c)'),

            TD::make('created_at', 'Дата створення')
                ->sort()
                ->render(fn(Log $log) => $log->created_at->toDateTimeString()),

            TD::make('Дія')
                ->align(TD::ALIGN_CENTER)
                ->width('100px')
                ->render(fn(Log $log) => Button::make(__('Delete'))
                    ->icon('trash')
                    ->confirm(
                        'Це історія використання вашим ботом. Ви точно бажаєте видалити?'
                    )
                    ->method('remove', [
                        'id' => $log->id,
                    ])),

            /*            TD::make('delete_action', 'Видалення')->render(function (Log $log) {
                            return ModalToggle::make('Видалити')
                                ->modal('deleteLog')
                                ->method('remove', [
                                    'id' => $log->id,
                                ])
                                ->modalTitle("Ви впевнені видалити лог (\"$log->created_at\")?")
                                ->asyncParameters([
                                    'id' => $log->id
                                ])->icon('trash');
                        })->alignRight()->width(100),*/
        ];
    }
}

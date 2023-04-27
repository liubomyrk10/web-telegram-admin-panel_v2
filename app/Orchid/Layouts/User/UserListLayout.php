<?php

declare(strict_types=1);

namespace App\Orchid\Layouts\User;

use Orchid\Platform\Models\User;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Actions\DropDown;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Actions\ModalToggle;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Layouts\Persona;
use Orchid\Screen\Layouts\Table;
use Orchid\Screen\TD;

class UserListLayout extends Table
{
    /**
     * @var string
     */
    public $target = 'users';

    /**
     * @return TD[]
     */
    public function columns(): array
    {
        return [
            TD::make('name', __('Name'))
                ->sort()
                ->cantHide()
                ->filter(Input::make())
                ->render(fn(User $user) => new Persona($user->presenter())),

            TD::make('email', __('Email'))
                ->sort()
                ->cantHide()
                ->filter(Input::make())
                ->render(fn(User $user) => ModalToggle::make($user->email)
                    ->modal('asyncEditUserModal')
                    ->modalTitle($user->presenter()->title())
                    ->method('saveUser')
                    ->asyncParameters([
                        'user' => $user->id,
                    ])),

            TD::make('telegram_id', __('Telegram ID'))
                ->sort(),
            TD::make('first_name', __("Ім'я"))
                ->sort(),
            TD::make('username', __("Нік"))
                ->sort(),

            TD::make('updated_at', __('Last edit'))
                ->sort()
                ->render(fn(User $user) => $user->updated_at->toDateTimeString()),

            TD::make('Дії')
                ->align(TD::ALIGN_CENTER)
                ->width('100px')
                ->render(fn(User $user) => DropDown::make()
                    ->icon('options-vertical')
                    ->list([
                        Link::make(__('Edit'))
                            ->route('platform.systems.users.edit', $user->id)
                            ->icon('pencil'),

                        Button::make(__('Delete'))
                            ->icon('trash')
                            ->confirm(
                                'Після видалення облікового запису всі його ресурси та дані будуть безповоротно видалені. Перед видаленням облікового запису, будь ласка, завантажте всі дані або інформацію, які ви хочете зберегти.'
                            )
                            ->method('remove', [
                                'id' => $user->id,
                            ]),
                    ])),
        ];
    }
}

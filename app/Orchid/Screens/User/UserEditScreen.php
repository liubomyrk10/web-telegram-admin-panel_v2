<?php

declare(strict_types=1);

namespace App\Orchid\Screens\User;

use App\Http\Requests\UserRequest;
use App\Orchid\Layouts\Role\RolePermissionLayout;
use App\Orchid\Layouts\User\UserEditLayout;
use App\Orchid\Layouts\User\UserPasswordLayout;
use App\Orchid\Layouts\User\UserRoleLayout;
use App\Orchid\Layouts\User\UserTelegramEditLayout;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Hash;
use Orchid\Access\Impersonation;
use Orchid\Platform\Models\User;
use Orchid\Screen\Action;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Screen;
use Orchid\Support\Color;
use Orchid\Support\Facades\Layout;
use Orchid\Support\Facades\Toast;

class UserEditScreen extends Screen
{
    /**
     * @var User
     */
    public $user;

    /**
     * Fetch data to be displayed on the screen.
     *
     * @param User $user
     *
     * @return array
     */
    public function query(User $user): iterable
    {
        $user->load(['roles']);

        return [
            'user' => $user,
            'permission' => $user->getStatusPermission(),
        ];
    }

    /**
     * The name of the screen displayed in the header.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return $this->user->exists ? 'Редагувати користувача' : 'Створити користувача';
    }

    /**
     * Display header description.
     *
     * @return string|null
     */
    public function description(): ?string
    {
        return "Такі дані, як ім'я, адреса електронної пошти та пароль";
    }

    /**
     * @return iterable|null
     */
    public function permission(): ?iterable
    {
        return [
            'platform.systems.users',
        ];
    }

    /**
     * The screen's action buttons.
     *
     * @return Action[]
     */
    public function commandBar(): iterable
    {
        return [
            Button::make(__('Impersonate user'))
                ->icon('login')
                ->confirm(__('Ви можете повернутися до початкового стану, вийшовши з системи.'))
                ->method('loginAs')
                ->canSee($this->user->exists && \request()->user()->id !== $this->user->id),

            Button::make(__('Remove'))
                ->icon('trash')
                ->confirm(
                    'Після видалення облікового запису всі його ресурси та дані будуть безповоротно видалені. Перед видаленням облікового запису, будь ласка, завантажте всі дані або інформацію, які ви хочете зберегти.'
                )
                ->method('remove')
                ->canSee($this->user->exists),

            Button::make(__('Save'))
                ->icon('check')
                ->method('save'),
        ];
    }

    /**
     * @return \Orchid\Screen\Layout[]
     */
    public function layout(): iterable
    {
        return [

            Layout::block(UserEditLayout::class)
                ->title('Інформація про профіль')
                ->description('Оновіть інформацію про профіль вашого облікового запису та адресу електронної пошти.')
                ->commands(
                    Button::make(__('Save'))
                        ->type(Color::DEFAULT())
                        ->icon('check')
                        ->canSee($this->user->exists)
                        ->method('save')
                ),

            Layout::block(UserTelegramEditLayout::class)
                ->title('Інформація телеграм профілю')
                ->description('Ваша інформація адміністратора ботів та каналів із самого телеграму.')
                ->commands(
                    Button::make(__('Save'))
                        ->type(Color::DEFAULT())
                        ->icon('check')
                        ->canSee($this->user->exists)
                        ->method('save')
                ),

            Layout::block(UserPasswordLayout::class)
                ->title(__('Password'))
                ->description(
                    'Переконайтеся, що ваш обліковий запис використовує довгий випадковий пароль, щоб залишатися в безпеці.'
                )
                ->commands(
                    Button::make(__('Save'))
                        ->type(Color::DEFAULT())
                        ->icon('check')
                        ->canSee($this->user->exists)
                        ->method('save')
                ),

            Layout::block(UserRoleLayout::class)
                ->title(__('Roles'))
                ->description('Роль визначає набір завдань, які може виконувати користувач, якому призначено цю роль.')
                ->commands(
                    Button::make(__('Save'))
                        ->type(Color::DEFAULT())
                        ->icon('check')
                        ->canSee($this->user->exists)
                        ->method('save')
                ),

            Layout::block(RolePermissionLayout::class)
                ->title('Дозволи')
                ->description('Дозволити користувачеві виконувати деякі дії, які не передбачені його ролями')
                ->commands(
                    Button::make(__('Save'))
                        ->type(Color::DEFAULT())
                        ->icon('check')
                        ->canSee($this->user->exists)
                        ->method('save')
                ),

        ];
    }

    /**
     * @param User $user
     * @param UserRequest $request
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function save(User $user, UserRequest $request)
    {
        $validatedUser = $request->validated()['user'];
        $user->name = $validatedUser['name'];
        $user->email = $validatedUser['email'];
        $user->telegram_id = $validatedUser['telegram_id'];
        $user->first_name = $validatedUser['first_name'];
        $user->last_name = $validatedUser['last_name'];
        $user->username = $validatedUser['username'];
        $user->photo_url = $validatedUser['photo_url'];

        $permissions = collect($request->get('permissions'))
            ->map(fn($value, $key) => [base64_decode($key) => $value])
            ->collapse()
            ->toArray();

        $user->when($request->filled('user.password'), function (Builder $builder) use ($request) {
            $builder->getModel()->password = Hash::make($request->input('user.password'));
        });

        $user
            ->fill($request->collect('user')->except(['password', 'permissions', 'roles'])->toArray())
            ->fill(['permissions' => $permissions])
            ->save();

        $user->replaceRoles($request->input('user.roles'));

        Toast::info(__('User was saved.'));

        return redirect()->route('platform.systems.users');
    }

    /**
     * @param User $user
     *
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     *
     */
    public function remove(User $user)
    {
        $user->delete();

        Toast::info('Користувача було видалено');

        return redirect()->route('platform.systems.users');
    }

    /**
     * @param User $user
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function loginAs(User $user)
    {
        Impersonation::loginAs($user);

        Toast::info('Ви зараз видаєте себе за цього користувача');

        return redirect()->route(config('platform.index'));
    }
}

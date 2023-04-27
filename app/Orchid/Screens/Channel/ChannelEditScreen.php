<?php

namespace App\Orchid\Screens\Channel;

use App\Http\Requests\ChannelRequest;
use App\Models\Bot;
use App\Models\Channel;
use App\Orchid\Layouts\Channel\ChannelEditLayout;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Screen;
use Orchid\Support\Color;
use Orchid\Support\Facades\Layout;
use Orchid\Support\Facades\Toast;

class ChannelEditScreen extends Screen
{
    public Channel $channel;

    /**
     * Fetch data to be displayed on the screen.
     *
     * @return array
     */
    public function query(Channel $channel): iterable
    {
        // $bot->load(['subreddits']);

        return [
            'channel' => $channel
        ];
    }

    /**
     * The name of the screen displayed in the header.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return $this->channel->exists ? 'Редагувати канал' : 'Додати канал';
    }

    /**
     * Display header description.
     *
     * @return string|null
     */
    public function description(): ?string
    {
        return "Інформація про канал.";
    }

    /**
     * @return iterable|null
     */
    public function permission(): ?iterable
    {
        return [
            'platform.channels',
        ];
    }

    /**
     * The screen's action buttons.
     *
     * @return \Orchid\Screen\Action[]
     */
    public function commandBar(): iterable
    {
        return [
            Button::make(__('Remove'))
                ->icon('trash')
                ->confirm(
                    'Якщо ви випадково видалите канал, але захочете його відновити - зверніться до супер адміністратора.'
                )
                ->method('remove')
                ->canSee($this->channel->exists),

            Button::make(__('Save'))
                ->icon('check')
                ->method('save'),
        ];
    }

    /**
     * The screen's layout elements.
     *
     * @return \Orchid\Screen\Layout[]|string[]
     */
    public function layout(): iterable
    {
        return [
            Layout::block(ChannelEditLayout::class)
                ->title('Інформація про канал')
                ->description('Оновіть інформацію про ваш канал.')
                ->commands(
                    Button::make(__('Save'))
                        ->type(Color::DEFAULT())
                        ->icon('check')
                        ->canSee($this->channel->exists)
                        ->method('save')
                ),
        ];
    }

    public function save(Channel $channel, ChannelRequest $request)
    {
        $validatedBot = $request->validated()['channel'];
        $channel->telegram_id = $validatedBot['telegram_id'];
        $channel->bot_id = $validatedBot['bot_id'];
        $channel->title = $validatedBot['title'];
        $channel->username = $validatedBot['username'];
        $channel->description = $validatedBot['description'];
        $channel->photo = $validatedBot['photo'];
        $channel->member_count = $validatedBot['member_count'];
        $channel->is_public = $validatedBot['is_public'];
        $channel->invite_link = $validatedBot['invite_link'];

        $channel->save();

        Toast::info('Канал успішно збережено!');

        return redirect()->route('platform.channels');
    }


    public function remove(Bot $bot)
    {
        $bot->delete();

        Toast::info('Канал було видалено успішно!');

        return redirect()->route('platform.channels');
    }
}

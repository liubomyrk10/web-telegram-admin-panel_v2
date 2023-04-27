<?php

namespace App\Orchid\Screens\Subreddit;

use App\Http\Requests\SubredditRequest;
use App\Models\Subreddit;
use App\Orchid\Layouts\Subreddit\SubredditCreateOrUpdate;
use App\Orchid\Layouts\Subreddit\SubredditListLayout;
use Illuminate\Http\Request;
use Orchid\Screen\Actions\ModalToggle;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Screen;
use Orchid\Support\Facades\Layout;
use Orchid\Support\Facades\Toast;

class SubredditListScreen extends Screen
{
    /**
     * Fetch data to be displayed on the screen.
     *
     * @return array
     */
    public function query(): iterable
    {
        return [
            //'subreddit' => Subreddit::query()->find(1),
            'subreddits' => Subreddit::filters()->defaultSort('name', 'desc')->paginate(10)
        ];
    }

    /**
     * The name of the screen displayed in the header.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return 'Субреддіти';
    }

    /**
     * Display header description.
     *
     * @return string|null
     */
    public function description(): ?string
    {
        return 'Список підтримуваних субреддітів - це джерело авто-генерованого контенту.';
    }

    /**
     * The screen's action buttons.
     *
     * @return \Orchid\Screen\Action[]
     */
    public function commandBar(): iterable
    {
        return [
            ModalToggle::make('Створити')->modal('createSubreddit')
                ->method('createOrUpdate')->icon('plus')
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
            SubredditListLayout::class,
            Layout::modal(
                'createSubreddit',
                SubredditCreateOrUpdate::class
            )->title('Створення субреддіта')->applyButton('Створити'),
            Layout::modal(
                'editSubreddit',
                SubredditCreateOrUpdate::class
            )->applyButton('Відредагувати')->async('asyncGetSubreddit'),
            Layout::modal(
                'deleteSubreddit',
                Layout::rows([
                    Input::make('subreddit.id')->type('hidden')
                ])
            )->applyButton('Так, видали')->closeButton('Ні, скасувати')->async('asyncGetSubreddit')
        ];
    }

    public function asyncGetSubreddit(Subreddit $subreddit): array
    {
        return [
            'subreddit' => $subreddit
        ];
    }

    public function createOrUpdate(SubredditRequest $request): void
    {
        $id = $request->input('subreddit.id');
        Subreddit::query()->updateOrCreate([
            'id' => $id
        ], $request->validated()['subreddit']);

        is_null($id) ? Toast::info('Субреддіт додано успішно!') : Toast::info('Субреддіт відредаговано успішно!');
    }

    public function delete(Request $request)
    {
        $id = $request->input('subreddit.id');
        Subreddit::query()->find($id)->delete();
        Toast::info('Субреддіт успішно видалений');
    }
}

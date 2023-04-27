<?php

namespace App\Orchid\Layouts\Subreddit;

use App\Models\Subreddit;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;
use Orchid\Screen\Actions\ModalToggle;
use Orchid\Screen\Layouts\Table;
use Orchid\Screen\TD;

class SubredditListLayout extends Table
{
    /**
     * Data source.
     *
     * The name of the key to fetch it from the query.
     * The results of which will be elements of the table.
     *
     * @var string
     */
    protected $target = 'subreddits';

    /**
     * Get the table cells to be displayed.
     *
     * @return TD[]
     */
    protected function columns(): iterable
    {
        return [
            TD::make('id', 'ID')->sort()->defaultHidden(),
            TD::make('name', 'Назва')->sort()->filter(),
            TD::make('description', 'Опис')->render(
                fn(Subreddit $subreddit) => Str::limit($subreddit->description, 50)
            ),
            TD::make('created_at', 'Дата створено')->render(
                fn(Subreddit $subreddit) => Carbon::parse($subreddit->created_at)->format('d.m.Y H:i:s')
            )->defaultHidden(),
            TD::make('updated_at', 'Дата оновлено')->sort()->render(
                fn(Subreddit $subreddit) => Carbon::parse($subreddit->updated_at)->format('d.m.Y H:i:s')
            )->defaultHidden(),
            TD::make('edit_action', 'Редагування')->render(function (Subreddit $subreddit) {
                return ModalToggle::make('Редагувати')
                    ->modal('editSubreddit')
                    ->method('createOrUpdate')
                    ->modalTitle("Редагування субредіта \"$subreddit->name\"")
                    ->asyncParameters([
                        'subreddit' => $subreddit->id
                    ])->icon('pencil');
            })->alignRight()->width(100),
            TD::make('delete_action', 'Видалення')->render(function (Subreddit $subreddit) {
                return ModalToggle::make('Видалити')
                    ->modal('deleteSubreddit')
                    ->method('delete')
                    ->modalTitle("Ви впевнені видалити субредіта \"$subreddit->name\"?")
                    ->asyncParameters([
                        'group' => $subreddit->id
                    ])->icon('trash');
            })->alignRight()->width(100),
        ];
    }
}

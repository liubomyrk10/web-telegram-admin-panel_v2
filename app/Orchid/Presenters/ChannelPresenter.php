<?php

namespace App\Orchid\Presenters;

use Illuminate\Support\Str;
use Laravel\Scout\Builder;
use Orchid\Screen\Contracts\Personable;
use Orchid\Screen\Contracts\Searchable;
use Orchid\Support\Presenter;

class ChannelPresenter extends Presenter implements Searchable, Personable
{

    public function perSearchShow(): int
    {
        return 3;
    }

    public function searchQuery(string $query = null): Builder
    {
        return $this->entity->search($query);
    }

    public function label(): string
    {
        return 'Канали';
    }

    public function title(): string
    {
        return $this->entity->title;
    }

    public function subTitle(): string
    {
        $description = "{$this->entity->username} - {$this->entity->member_count}";

        return (string)Str::of($description)
            ->limit(30)
            ->whenEmpty(fn() => $this->entity->username);
    }

    public function url(): string
    {
        return route('platform.channels.edit', $this->entity);
    }

    public function image(): ?string
    {
        return $this->photo;
    }
}

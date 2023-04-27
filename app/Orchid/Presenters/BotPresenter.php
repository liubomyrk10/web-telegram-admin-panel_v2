<?php

namespace App\Orchid\Presenters;

use Illuminate\Support\Str;
use Laravel\Scout\Builder;
use Orchid\Screen\Contracts\Personable;
use Orchid\Screen\Contracts\Searchable;
use Orchid\Support\Presenter;

class BotPresenter extends Presenter implements Searchable, Personable
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
        return 'Боти';
    }

    public function title(): string
    {
        return $this->entity->name;
    }

    public function subTitle(): string
    {
        $description = "Нік: {$this->entity->username}. {$this->entity->description}";

        return (string)Str::of($description)
            ->limit(30)
            ->whenEmpty(fn() => $this->entity->username);
    }

    public function url(): string
    {
        return route('platform.bots.edit', $this->entity);
    }

    public function image(): ?string
    {
        return $this->avatar;
    }
}

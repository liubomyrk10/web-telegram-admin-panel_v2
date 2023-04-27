<?php

namespace App\Models;

use App\Orchid\Presenters\ChannelPresenter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Orchid\Filters\Filterable;
use Orchid\Screen\AsSource;

class Channel extends Model
{
    use HasFactory;
    use AsSource;
    use Filterable;

    public function bot(): BelongsTo
    {
        return $this->belongsTo(Bot::class);
    }

    public function presenter(): ChannelPresenter
    {
        return new ChannelPresenter($this);
    }

    /**
     * Задає форматування для атрибута `member_count`.
     *
     * @return string
     */
    /*    public function getMemberCountAttribute(): string
        {
            return number_format($this->member_count, 0, '.', ' ');
        }*/

    protected $allowedFilters = [
        'title',
        'username'
    ];

    protected $allowedSorts = [
        'title',
        'username',
        'member_count',
        'updated_at'
    ];

    public function getTelegramChannelIdAttribute(): string
    {
        return '-100' . $this->telegram_id;
    }

    public function scopeOnlyOwner(Builder $query): Builder
    {
        return $query->with('bot')->whereHas('bot', function ($query) {
            $query->where('user_id', auth()->user()->id);
        });
    }

    public function scopeCountByOwner(Builder $query): int
    {
        return $query->with('bot')->whereHas('bot', function ($query) {
            $query->where('user_id', auth()->user()->id);
        })->count();
    }
}

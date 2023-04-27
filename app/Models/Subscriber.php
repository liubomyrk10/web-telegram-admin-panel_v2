<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;
use Orchid\Filters\Filterable;
use Orchid\Metrics\Chartable;
use Orchid\Screen\AsSource;


class Subscriber extends Model
{
    use HasFactory;
    use AsSource;
    use Filterable;
    use Chartable;

    protected $guarded = [];

    protected $appends = ['full_name'];

    public function bot(): BelongsTo
    {
        return $this->belongsTo(Bot::class);
    }

    public function logs(): HasMany
    {
        return $this->hasMany(Log::class);
    }

    public function getFullNameAttribute(): string
    {
        return $this->first_name . ' ' . $this->last_name;
    }

    protected $allowedFilters = [
        'bot_id',
        'username',
        'lang',
        'is_blocked'
    ];

    protected $allowedSorts = [
        'bot_id',
        'username',
        'lang',
        'updated_at'
    ];

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

    public function scopeWeakCountByOwner(Builder $query): int
    {
        $startDate = Carbon::today()->subDays(7);
        $endDate = Carbon::today();

        return $query->with('bot')->whereHas('bot', function ($query) {
            $query->where('user_id', auth()->user()->id);
        })->whereBetween('created_at', [$startDate, $endDate])->count();
    }
}

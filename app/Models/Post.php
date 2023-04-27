<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;
use Orchid\Filters\Filterable;
use Orchid\Metrics\Chartable;
use Orchid\Screen\AsSource;

class Post extends Model
{
    use HasFactory;
    use SoftDeletes;
    use AsSource;
    use Filterable;
    use Chartable;

    protected $guarded = [];

    protected $casts = [
        'send_time_taken' => 'decimal:4',
    ];

    public function subreddit(): BelongsTo
    {
        return $this->belongsTo(Subreddit::class);
    }

    public function bots(): BelongsToMany
    {
        return $this->belongsToMany(Bot::class);
    }

    protected $allowedFilters = [
        'type',
        'title',
    ];

    protected $allowedSorts = [
        'type',
        'title',
        'is_nsfw',
        'is_spoiler',
        'updated_at',
    ];

    public function scopeOnlyOwner(Builder $query): Builder
    {
        return $query->with('bots')->whereHas('bots', function ($query) {
            $query->where('user_id', auth()->user()->id);
        });
    }

    public function scopeCountByOwner(Builder $query): int
    {
        return $query->with('bots')->whereHas('bots', function ($query) {
            $query->where('user_id', auth()->user()->id);
        })->count();
    }

    public function scopeWeakCountByOwner(Builder $query): int
    {
        $startDate = Carbon::today()->subDays(7);
        $endDate = Carbon::today();

        return $query->with('bots')->whereHas('bots', function ($query) {
            $query->where('user_id', auth()->user()->id);
        })->whereBetween('created_at', [$startDate, $endDate])->count();
    }
}

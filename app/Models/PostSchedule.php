<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;
use Orchid\Filters\Filterable;
use Orchid\Screen\AsSource;

class PostSchedule extends Model
{
    use HasFactory;
    use AsSource;
    use Filterable;

    protected $guarded = [];

    protected $table = "posts_schedule";

    protected $casts = [
        'post_time' => 'datetime',
    ];

    public function channel(): BelongsTo
    {
        return $this->belongsTo(Channel::class);
    }

    public function post(): BelongsTo
    {
        return $this->belongsTo(Post::class);
    }

    protected $allowedFilters = [
        'channel_id',
        'post_id'
    ];

    protected $allowedSorts = [
        'channel_id',
        'post_id',
        'post_time',
        'updated_at'
    ];

    public function scopeCountByOwner(Builder $query): int
    {
        $today = Carbon::today();

        return $query->with('channel')->whereHas('channel.bot', function ($query) {
            $query->where('user_id', auth()->user()->id);
        })->whereDate('created_at', '>=', $today)->count();
    }

    public function scopePostedCountByOwner(Builder $query): int
    {
        $today = Carbon::today();

        return $query->with('channel')->whereHas('channel.bot', function ($query) {
            $query->where('user_id', auth()->user()->id);
        })->whereDate('created_at', '<', $today)->count();
    }
}

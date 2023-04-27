<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;
use Orchid\Filters\Filterable;
use Orchid\Metrics\Chartable;
use Orchid\Screen\AsSource;

class Log extends Model
{
    use HasFactory;
    use AsSource;
    use Filterable;
    use Chartable;

    protected $guarded = [];

    protected $appends = ['send_time_taken_in_seconds'];

    protected $casts = [
        'send_time_taken' => 'decimal:4',
    ];

    public function subscriber(): BelongsTo
    {
        return $this->belongsTo(Subscriber::class);
    }

    public function getSendTimeTakenInSecondsAttribute(): string
    {
        $carbon = Carbon::createFromTimestamp($this->send_time_taken / 1000);
        $seconds = $carbon->format('U');
        $microseconds = $carbon->microsecond;
        $value = $seconds . '.' . $microseconds;
        return rtrim($value, '0');
    }

    protected $allowedFilters = [
        'chat_type',
        'command'
    ];

    protected $allowedSorts = [
        'chat_type',
        'command',
        'send_time_taken',
    ];

    public function scopeOnlyOwner(Builder $query): Builder
    {
        return $query->with('subscriber')->whereHas('subscriber.bot', function ($query) {
            $query->where('user_id', auth()->user()->id);
        });
    }

    public function scopeCountByOwner(Builder $query): int
    {
        return $query->with('subscriber')->whereHas('subscriber.bot', function ($query) {
            $query->where('user_id', auth()->user()->id);
        })->count();
    }

    public function scopeWeakCountByOwner(Builder $query): int
    {
        $startDate = Carbon::today()->subDays(7);
        $endDate = Carbon::today();

        return $query->with('subscriber')->whereHas('subscriber.bot', function ($query) {
            $query->where('user_id', auth()->user()->id);
        })->whereBetween('created_at', [$startDate, $endDate])->count();
    }
}

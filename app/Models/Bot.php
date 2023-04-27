<?php

namespace App\Models;

use App\Orchid\Presenters\BotPresenter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Orchid\Filters\Filterable;
use Orchid\Screen\AsSource;

class Bot extends Model
{
    use HasFactory;
    use SoftDeletes;
    use AsSource;
    use Filterable;

    protected $guarded = ["telegram_id"];


    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function subscribers(): hasMany
    {
        return $this->hasMany(Subscriber::class);
    }

    public function posts(): belongsToMany
    {
        return $this->belongsToMany(Post::class);
    }

    public function channels(): hasMany
    {
        return $this->hasMany(Channel::class);
    }

    public function subreddits(): BelongsToMany
    {
        return $this->belongsToMany(Subreddit::class);
    }

    public function pots(): BelongsToMany
    {
        return $this->belongsToMany(Post::class);
    }

    public function presenter(): BotPresenter
    {
        return new BotPresenter($this);
    }

    protected $allowedFilters = [
        'name',
        'username'
    ];

    protected $allowedSorts = [
        'name',
        'username',
        'updated_at'
    ];

    public function scopeOnlyOwner(Builder $query): Builder
    {
        return $query->where('user_id', auth()->user()->id);
    }

    public function scopeFindByToken(Builder $query, string $token): Model
    {
        return $query->where('token', $token)->firstOrFail();
    }

    public function scopeFindByUsername(Builder $query, string $username): Model
    {
        return $query->where('username', $username)->firstOrFail();
    }

    public function scopeCountByOwner(Builder $query): int
    {
        return $query->where('user_id', auth()->user()->id)->count();
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Orchid\Filters\Filterable;
use Orchid\Screen\AsSource;


class Subreddit extends Model
{
    use HasFactory;
    use AsSource;
    use Filterable;

    protected $guarded = [];


    public function posts(): hasMany
    {
        return $this->hasMany(Post::class);
    }

    public function bots(): BelongsToMany
    {
        return $this->belongsToMany(Bot::class);
    }

    protected $allowedFilters = [
        'name'
    ];

    protected $allowedSorts = [
        'name',
        'updated_at'
    ];
}

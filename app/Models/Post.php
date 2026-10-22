<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Post extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'title',
        'description',
        'status',
    ];

    public function scopeToday(Builder $query): Builder
    {
        return $query->whereDate('created_at', today());
    }

    public function scopeSearch(Builder $query, string $keyword): Builder
    {
        return $query->where('title', 'like', "%{$keyword}%");
    }

    public function scopeLatest(Builder $query): Builder
    {
        return $query->orderBy('created_at', 'desc');
    }

    public function scopePublished(Builder $query): Builder
    {
        return $query->where('status', 'published');
    }
}

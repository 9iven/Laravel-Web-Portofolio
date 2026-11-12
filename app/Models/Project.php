<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Project extends Model
{
    protected $fillable = [
        'judul',
        'slug',
        'category_id',
        'kategori',
        'short_description',
        'deskripsi',
        'status',
        'project_date',
        'is_featured',
        'tautan',
        'repository_url',
        'thumbnail',
    ];

    protected function casts(): array
    {
        return [
            'project_date' => 'date',
            'is_featured' => 'boolean',
        ];
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function technologies(): BelongsToMany
    {
        return $this->belongsToMany(Technology::class);
    }

    public function images(): HasMany
    {
        return $this->hasMany(ProjectImage::class)->orderBy('sort_order');
    }

    public function getThumbnailUrlAttribute(): ?string
    {
        $path = $this->thumbnail ?: $this->images->first()?->image;

        return $path ? asset('storage/'.$path) : null;
    }
}

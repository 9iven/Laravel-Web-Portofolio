<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Project extends Model
{
    protected $fillable = [
        'name',
        'judul',
        'kategori',
        'deskripsi',
        'teknologi',
        'tautan',
        'thumbnail',
        'category_id',
        'status',
    ];

    public function setNameAttribute($value): void
    {
        $this->attributes['judul'] = $value;
    }

    public function getNameAttribute(): string
    {
        return $this->judul ?? '';
    }

    public function getThumbnailUrlAttribute(): ?string
    {
        $path = $this->thumbnail ?: $this->images->first()?->image;

        return $path ? asset('storage/'.$path) : null;
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function images(): HasMany
    {
        return $this->hasMany(ProjectImage::class)->orderBy('sort_order');
    }
}

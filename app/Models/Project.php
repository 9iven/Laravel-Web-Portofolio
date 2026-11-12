<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Project extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'category_id',
        'short_description',
        'description',
        'status',
        'project_date',
        'is_featured',
        'project_url',
        'repository_url',
        'thumbnail',
        'judul',
        'kategori',
        'deskripsi',
        'teknologi',
        'tautan',
    ];

    protected function casts(): array
    {
        return [
            'project_date' => 'date',
            'is_featured' => 'boolean',
        ];
    }

    protected static function booted(): void
    {
        static::saving(function (Project $project) {
            if (empty($project->kategori)) {
                $project->kategori = $project->category?->name ?? 'Umum';
            }
            if (empty($project->teknologi)) {
                $project->teknologi = 'Web';
            }
            if (empty($project->judul) && ! empty($project->name)) {
                $project->judul = $project->name;
            }
            if (empty($project->name) && ! empty($project->judul)) {
                $project->name = $project->judul;
            }
            if (empty($project->deskripsi) && ! empty($project->description)) {
                $project->deskripsi = $project->description;
            }
            if (empty($project->description) && ! empty($project->deskripsi)) {
                $project->description = $project->deskripsi;
            }
            if (empty($project->tautan) && ! empty($project->project_url)) {
                $project->tautan = $project->project_url;
            }
            if (empty($project->project_url) && ! empty($project->tautan)) {
                $project->project_url = $project->tautan;
            }
        });
    }

    public function setNameAttribute($value): void
    {
        $this->attributes['name'] = $value;
        $this->attributes['judul'] = $value;
        if (empty($this->attributes['slug']) && ! empty($value)) {
            $this->attributes['slug'] = Str::slug($value);
        }
    }

    public function setJudulAttribute($value): void
    {
        $this->attributes['judul'] = $value;
        if (empty($this->attributes['name'])) {
            $this->attributes['name'] = $value;
        }
        if (empty($this->attributes['slug']) && ! empty($value)) {
            $this->attributes['slug'] = Str::slug($value);
        }
    }

    public function getNameAttribute(): string
    {
        return $this->attributes['name'] ?? $this->attributes['judul'] ?? '';
    }

    public function getJudulAttribute(): string
    {
        return $this->attributes['judul'] ?? $this->attributes['name'] ?? '';
    }

    public function setDescriptionAttribute($value): void
    {
        $this->attributes['description'] = $value;
        $this->attributes['deskripsi'] = $value;
    }

    public function setDeskripsiAttribute($value): void
    {
        $this->attributes['deskripsi'] = $value;
        if (empty($this->attributes['description'])) {
            $this->attributes['description'] = $value;
        }
    }

    public function getDescriptionAttribute(): ?string
    {
        return $this->attributes['description'] ?? $this->attributes['deskripsi'] ?? null;
    }

    public function getDeskripsiAttribute(): ?string
    {
        return $this->attributes['deskripsi'] ?? $this->attributes['description'] ?? null;
    }

    public function setProjectUrlAttribute($value): void
    {
        $this->attributes['project_url'] = $value;
        $this->attributes['tautan'] = $value;
    }

    public function setTautanAttribute($value): void
    {
        $this->attributes['tautan'] = $value;
        if (empty($this->attributes['project_url'])) {
            $this->attributes['project_url'] = $value;
        }
    }

    public function getProjectUrlAttribute(): ?string
    {
        return $this->attributes['project_url'] ?? $this->attributes['tautan'] ?? null;
    }

    public function getTautanAttribute(): ?string
    {
        return $this->attributes['tautan'] ?? $this->attributes['project_url'] ?? null;
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

    public function technologies(): BelongsToMany
    {
        return $this->belongsToMany(Technology::class);
    }

    public function images(): HasMany
    {
        return $this->hasMany(ProjectImage::class)->orderBy('sort_order');
    }
}

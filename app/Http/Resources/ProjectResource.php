<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProjectResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->judul ?? $this->title,
            'slug' => $this->slug,
            'summary' => $this->short_description,
            'description' => $this->deskripsi,
            'thumbnail' => $this->thumbnail ? asset('storage/'.$this->thumbnail) : null,
            'category' => $this->category?->name,
            'technologies' => $this->technologies->pluck('name'),
            'project_date' => $this->project_date?->format('Y-m-d'),
            'repository_url' => $this->repository_url,
            'project_url' => $this->tautan ?? $this->project_url,
        ];
    }
}

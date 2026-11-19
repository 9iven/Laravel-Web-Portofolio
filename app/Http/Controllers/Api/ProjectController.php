<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProjectResource;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class ProjectController extends Controller
{
    public function index(Request $request): AnonymousResourceCollection
    {
        $query = Project::with(['category', 'technologies'])
            ->where('status', 'published')
            ->when($request->search, function ($q, $search) {
                $q->where(function ($w) use ($search) {
                    $w->where('judul', 'like', "%{$search}%")
                        ->orWhere('short_description', 'like', "%{$search}%")
                        ->orWhere('deskripsi', 'like', "%{$search}%");
                });
            })
            ->when($request->category, function ($q, $category) {
                $q->whereHas('category', function ($w) use ($category) {
                    $w->where('slug', $category);
                });
            })
            ->latest('project_date');

        $projects = $query->paginate(9)->withQueryString();

        return ProjectResource::collection($projects);
    }

    /**
     * Menampilkan detail satu proyek berdasarkan slug di URL.
     */
    public function show(Project $project): ProjectResource
    {
        // Tolak dengan 404 jika proyek berstatus draft
        abort_if($project->status !== 'published', 404);

        return new ProjectResource($project->load(['category', 'technologies']));
    }
}

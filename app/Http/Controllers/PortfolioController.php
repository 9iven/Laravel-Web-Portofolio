<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Project;
use App\Models\Technology;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PortfolioController extends Controller
{
    /**
     * Display a listing of public projects with search, filtering, and sorting.
     */
    public function projects(Request $request): View
    {
        $query = Project::with(['category', 'technologies', 'images'])
            ->where('status', 'published')
            ->when($request->search, fn ($q, $s) => $q->where(fn ($w) => $w->where('name', 'like', "%{$s}%")
                ->orWhere('judul', 'like', "%{$s}%")
                ->orWhere('short_description', 'like', "%{$s}%")
                ->orWhere('description', 'like', "%{$s}%")
                ->orWhere('deskripsi', 'like', "%{$s}%")))
            ->when($request->category, fn ($q, $c) => $q->whereHas('category', fn ($w) => $w->where('slug', $c)))
            ->when($request->technology, fn ($q, $t) => $q->whereHas('technologies', fn ($w) => $w->where('slug', $t)));

        switch ($request->query('sort')) {
            case 'terlama':
                $query->oldest('project_date')->oldest('id');
                break;
            case 'az':
                $query->orderBy('name', 'asc');
                break;
            case 'za':
                $query->orderBy('name', 'desc');
                break;
            case 'project_date':
                $query->orderByDesc('project_date')->latest('id');
                break;
            default:
                $query->latest('project_date')->latest('id');
        }

        $projects = $query->paginate(9)->withQueryString();
        $categories = Category::orderBy('name')->get();
        $technologies = Technology::orderBy('name')->get();

        return view('projects.index', compact('projects', 'categories', 'technologies'));
    }

    /**
     * Display the specified public project by slug.
     */
    public function show(Project $project): View
    {
        abort_if($project->status !== 'published', 404);

        $project->load(['category', 'technologies', 'images']);

        return view('projects.show', compact('project'));
    }
}

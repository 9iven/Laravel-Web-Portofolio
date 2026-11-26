<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Education;
use App\Models\Experience;
use App\Models\Project;
use App\Models\Skill;
use App\Models\Technology;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\View\View;

class PortfolioController extends Controller
{
    /**
     * Menampilkan halaman beranda dengan proyek unggulan dan daftar keahlian yang di-cache.
     */
    public function home(): View
    {
        $featured = Cache::remember('portfolio.featured', 1800, fn () => Project::with(['category', 'images'])
            ->where('status', 'published')
            ->where('is_featured', true)
            ->latest('project_date')
            ->latest('id')
            ->take(6)
            ->get());

        $skills = Cache::remember('portfolio.skills', 3600, fn () => Skill::orderByDesc('level')
            ->orderBy('name')
            ->get());

        return view('home', compact('featured', 'skills'));
    }

    /**
     * Menampilkan halaman tentang saya beserta pengalaman dan keahlian yang di-cache.
     */
    public function about(): View
    {
        $experiences = Cache::remember('portfolio.experiences', 3600, fn () => Experience::orderBy('sort_order')
            ->orderByDesc('start_date')
            ->get());

        $skills = Cache::remember('portfolio.skills', 3600, fn () => Skill::orderByDesc('level')
            ->orderBy('name')
            ->get());

        return view('about', compact('experiences', 'skills'));
    }

    /**
     * Menampilkan halaman riwayat pendidikan yang di-cache.
     */
    public function education(): View
    {
        $educations = Cache::remember('portfolio.educations', 3600, fn () => Education::orderBy('sort_order')
            ->orderByDesc('start_year')
            ->get());

        return view('education', compact('educations'));
    }

    /**
     * Menampilkan daftar proyek publik dengan pencarian, filter kategori & teknologi, serta pengurutan.
     */
    public function projects(Request $request): View
    {
        $query = Project::with(['category', 'technologies', 'images'])
            ->where('status', 'published')
            ->when($request->search, fn ($q, $s) => $q->where(fn ($w) => $w->where('judul', 'like', "%{$s}%")
                ->orWhere('short_description', 'like', "%{$s}%")
                ->orWhere('deskripsi', 'like', "%{$s}%")))
            ->when($request->category, fn ($q, $c) => $q->whereHas('category', fn ($w) => $w->where('slug', $c)))
            ->when($request->technology, fn ($q, $t) => $q->whereHas('technologies', fn ($w) => $w->where('slug', $t)));

        switch ($request->query('sort')) {
            case 'terlama':
                $query->oldest('project_date')->oldest('id');
                break;
            case 'az':
                $query->orderBy('judul', 'asc');
                break;
            case 'za':
                $query->orderBy('judul', 'desc');
                break;
            case 'project_date':
                $query->orderByDesc('project_date')->latest('id');
                break;
            default:
                $query->latest('project_date')->latest('id');
        }

        $projects = $query->paginate(9)->withQueryString();
        $categories = Cache::remember('portfolio.categories', 3600, fn () => Category::orderBy('name')->get());
        $technologies = Cache::remember('portfolio.technologies', 3600, fn () => Technology::orderBy('name')->get());

        return view('projects.index', compact('projects', 'categories', 'technologies'));
    }

    /**
     * Menampilkan halaman detail proyek publik berdasarkan slug di URL.
     */
    public function show(Project $project): View
    {
        abort_if($project->status !== 'published', 404);

        $project->load(['category', 'technologies', 'images']);

        return view('projects.show', compact('project'));
    }
}

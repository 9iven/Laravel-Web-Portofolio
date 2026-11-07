<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Project;
use App\Models\Skill;
use App\Models\Technology;
use Illuminate\Contracts\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        return view('dashboard', [
            'totalProjects' => Project::count(),
            'totalPublished' => Project::where('status', 'published')->count(),
            'totalDraft' => Project::where('status', 'draft')->count(),
            'totalCategories' => Category::count(),
            'totalTechnologies' => Technology::count(),
            'totalSkills' => Skill::count(),
            'categoriesWithCount' => Category::withCount('projects')->get(),
            'topSkills' => Skill::orderBy('level', 'desc')->take(5)->get(),
            'latestProjects' => Project::with('category')->latest()->take(5)->get(),
        ]);
    }
}

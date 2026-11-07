<?php

namespace App\Http\Controllers;

use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PortfolioController extends Controller
{
    /**
     * Display a listing of public projects.
     */
    public function projects(Request $request): View
    {
        $query = Project::with(['category', 'images'])->latest();

        if ($request->filled('status')) {
            $query->where('status', $request->query('status'));
        }

        $projects = $query->get();

        return view('projects.index', compact('projects'));
    }

    /**
     * Display the specified public project.
     */
    public function show(Project $project): View
    {
        $project->load(['category', 'images']);

        return view('projects.show', compact('project'));
    }
}

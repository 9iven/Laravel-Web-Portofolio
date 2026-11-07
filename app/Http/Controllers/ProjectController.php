<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Project;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class ProjectController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $projects = Project::with(['category', 'images'])->latest()->get();

        return view('projects.index', compact('projects'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $categories = Category::all();

        return view('projects.create', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        if ($request->filled('name') && ! $request->filled('judul')) {
            $request->merge(['judul' => $request->name]);
        }

        $validatedData = $request->validate([
            'judul' => 'required|min:3|max:255',
            'category_id' => 'required|exists:categories,id',
            'kategori' => 'nullable|string|max:100',
            'deskripsi' => 'required|min:10',
            'teknologi' => 'required|max:255',
            'tautan' => 'nullable|url|max:255',
            'status' => 'nullable|in:published,draft',
            'thumbnail' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048|dimensions:min_width=400,min_height=300',
        ], [
            'thumbnail.dimensions' => 'Ukuran thumbnail minimal berdimensi 400x300 px.',
            'thumbnail.max' => 'Ukuran file thumbnail maksimal 2 MB.',
        ]);

        if (empty($validatedData['kategori'])) {
            $category = Category::find($validatedData['category_id']);
            $validatedData['kategori'] = $category?->name ?? 'Umum';
        }

        if (empty($validatedData['status'])) {
            $validatedData['status'] = 'published';
        }

        if ($request->hasFile('thumbnail')) {
            $validatedData['thumbnail'] = $request->file('thumbnail')
                ->store('projects/thumbnails', 'public');
        }

        Project::create($validatedData);

        return redirect()->route('post.projects.index')->with('success', 'Project berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Project $project): View
    {
        $project->load(['category', 'images']);

        return view('projects.show', compact('project'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Project $project): View
    {
        $categories = Category::all();
        $project->load(['category', 'images']);

        return view('projects.edit', compact('project', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Project $project): RedirectResponse
    {
        if ($request->filled('name') && ! $request->filled('judul')) {
            $request->merge(['judul' => $request->name]);
        }

        $validatedData = $request->validate([
            'judul' => 'required|min:3|max:255',
            'category_id' => 'required|exists:categories,id',
            'kategori' => 'nullable|string|max:100',
            'deskripsi' => 'required|min:10',
            'teknologi' => 'required|max:255',
            'tautan' => 'nullable|url|max:255',
            'status' => 'nullable|in:published,draft',
            'thumbnail' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048|dimensions:min_width=400,min_height=300',
        ], [
            'thumbnail.dimensions' => 'Ukuran thumbnail minimal berdimensi 400x300 px.',
            'thumbnail.max' => 'Ukuran file thumbnail maksimal 2 MB.',
        ]);

        if (empty($validatedData['kategori'])) {
            $category = Category::find($validatedData['category_id']);
            $validatedData['kategori'] = $category?->name ?? 'Umum';
        }

        if ($request->hasFile('thumbnail')) {
            if ($project->thumbnail && Storage::disk('public')->exists($project->thumbnail)) {
                Storage::disk('public')->delete($project->thumbnail);
            }

            $validatedData['thumbnail'] = $request->file('thumbnail')
                ->store('projects/thumbnails', 'public');
        }

        $project->update($validatedData);

        return redirect()->route('post.projects.index')->with('success', 'Project berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Project $project): RedirectResponse
    {
        if ($project->thumbnail && Storage::disk('public')->exists($project->thumbnail)) {
            Storage::disk('public')->delete($project->thumbnail);
        }

        foreach ($project->images as $image) {
            if ($image->image && Storage::disk('public')->exists($image->image)) {
                Storage::disk('public')->delete($image->image);
            }
        }

        $project->delete();

        return redirect()->route('post.projects.index')->with('success', 'Project dihapus.');
    }
}

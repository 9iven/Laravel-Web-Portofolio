<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Project;
use App\Models\Technology;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\View\View;

class ProjectController extends Controller
{
    /**
     * Display a listing of the resource with search and status filtering (Latihan).
     */
    public function index(Request $request): View
    {
        $query = Project::with(['category', 'technologies', 'images'])
            ->when($request->search, fn ($q, $s) => $q->where(fn ($w) => $w->where('name', 'like', "%{$s}%")
                ->orWhere('judul', 'like', "%{$s}%")
                ->orWhere('short_description', 'like', "%{$s}%")
                ->orWhere('description', 'like', "%{$s}%")
                ->orWhere('deskripsi', 'like', "%{$s}%")))
            ->when($request->filled('status'), fn ($q) => $q->where('status', $request->query('status')))
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

        $projects = $query->paginate(10)->withQueryString();
        $categories = Category::orderBy('name')->get();
        $technologies = Technology::orderBy('name')->get();

        return view('projects.index', compact('projects', 'categories', 'technologies'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $categories = Category::orderBy('name')->get();
        $technologies = Technology::orderBy('name')->get();

        return view('projects.create', compact('categories', 'technologies'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        if ($request->filled('name') && ! $request->filled('judul')) {
            $request->merge(['judul' => $request->name]);
        } elseif ($request->filled('judul') && ! $request->filled('name')) {
            $request->merge(['name' => $request->judul]);
        }

        if ($request->filled('description') && ! $request->filled('deskripsi')) {
            $request->merge(['deskripsi' => $request->description]);
        } elseif ($request->filled('deskripsi') && ! $request->filled('description')) {
            $request->merge(['description' => $request->deskripsi]);
        }

        if ($request->filled('project_url') && ! $request->filled('tautan')) {
            $request->merge(['tautan' => $request->project_url]);
        } elseif ($request->filled('tautan') && ! $request->filled('project_url')) {
            $request->merge(['project_url' => $request->tautan]);
        }

        $validatedData = $request->validate([
            'name' => 'required|max:255|unique:projects,name',
            'judul' => 'nullable|max:255',
            'category_id' => 'required|exists:categories,id',
            'kategori' => 'nullable|string|max:100',
            'short_description' => 'nullable|max:255',
            'description' => 'nullable|string',
            'deskripsi' => 'nullable|string',
            'teknologi' => 'nullable|string|max:255',
            'project_date' => 'nullable|date',
            'is_featured' => 'nullable|boolean',
            'project_url' => 'nullable|url|max:255',
            'repository_url' => 'nullable|url|max:255',
            'tautan' => 'nullable|url|max:255',
            'status' => 'required|in:draft,published',
            'thumbnail' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048|dimensions:min_width=400,min_height=300',
            'technologies' => 'nullable|array',
            'technologies.*' => 'exists:technologies,id',
        ], [
            'thumbnail.dimensions' => 'Ukuran thumbnail minimal berdimensi 400x300 px.',
            'thumbnail.max' => 'Ukuran file thumbnail maksimal 2 MB.',
        ]);

        $nameVal = $validatedData['name'] ?? $validatedData['judul'];
        $validatedData['name'] = $nameVal;
        $validatedData['judul'] = $nameVal;
        $validatedData['slug'] = Str::slug($nameVal);

        if (empty($validatedData['kategori'])) {
            $category = Category::find($validatedData['category_id']);
            $validatedData['kategori'] = $category?->name ?? 'Umum';
        }

        if ($request->hasFile('thumbnail')) {
            $validatedData['thumbnail'] = $request->file('thumbnail')
                ->store('projects/thumbnails', 'public');
        }

        $technologyIds = $validatedData['technologies'] ?? [];
        unset($validatedData['technologies']);

        $project = Project::create($validatedData);

        if (! empty($technologyIds)) {
            $project->technologies()->sync($technologyIds);
        }

        return redirect()->route('post.projects.index')->with('success', 'Project berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Project $project): View
    {
        $project->load(['category', 'technologies', 'images']);

        return view('projects.show', compact('project'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Project $project): View
    {
        $categories = Category::orderBy('name')->get();
        $technologies = Technology::orderBy('name')->get();
        $project->load(['category', 'technologies', 'images']);

        return view('projects.edit', compact('project', 'categories', 'technologies'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Project $project): RedirectResponse
    {
        if ($request->filled('name') && ! $request->filled('judul')) {
            $request->merge(['judul' => $request->name]);
        } elseif ($request->filled('judul') && ! $request->filled('name')) {
            $request->merge(['name' => $request->judul]);
        }

        if ($request->filled('description') && ! $request->filled('deskripsi')) {
            $request->merge(['deskripsi' => $request->description]);
        } elseif ($request->filled('deskripsi') && ! $request->filled('description')) {
            $request->merge(['description' => $request->deskripsi]);
        }

        if ($request->filled('project_url') && ! $request->filled('tautan')) {
            $request->merge(['tautan' => $request->project_url]);
        } elseif ($request->filled('tautan') && ! $request->filled('project_url')) {
            $request->merge(['project_url' => $request->tautan]);
        }

        $validatedData = $request->validate([
            'name' => 'required|max:255|unique:projects,name,'.$project->id,
            'judul' => 'nullable|max:255',
            'category_id' => 'required|exists:categories,id',
            'kategori' => 'nullable|string|max:100',
            'short_description' => 'nullable|max:255',
            'description' => 'nullable|string',
            'deskripsi' => 'nullable|string',
            'teknologi' => 'nullable|string|max:255',
            'project_date' => 'nullable|date',
            'is_featured' => 'nullable|boolean',
            'project_url' => 'nullable|url|max:255',
            'repository_url' => 'nullable|url|max:255',
            'tautan' => 'nullable|url|max:255',
            'status' => 'required|in:draft,published',
            'thumbnail' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048|dimensions:min_width=400,min_height=300',
            'technologies' => 'nullable|array',
            'technologies.*' => 'exists:technologies,id',
        ], [
            'thumbnail.dimensions' => 'Ukuran thumbnail minimal berdimensi 400x300 px.',
            'thumbnail.max' => 'Ukuran file thumbnail maksimal 2 MB.',
        ]);

        $nameVal = $validatedData['name'] ?? $validatedData['judul'];
        $validatedData['name'] = $nameVal;
        $validatedData['judul'] = $nameVal;
        $validatedData['slug'] = Str::slug($nameVal);

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

        $technologyIds = $validatedData['technologies'] ?? [];
        unset($validatedData['technologies']);

        $project->update($validatedData);
        $project->technologies()->sync($technologyIds);

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

        $project->technologies()->detach();
        $project->delete();

        return redirect()->route('post.projects.index')->with('success', 'Project dihapus.');
    }
}

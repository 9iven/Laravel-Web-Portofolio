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
     * Menampilkan daftar proyek untuk admin (dengan fitur pencarian dan filter status).
     */
    public function index(Request $request): View
    {
        $query = Project::with(['category', 'technologies', 'images'])
            ->when($request->search, fn ($q, $s) => $q->where(fn ($w) => $w->where('judul', 'like', "%{$s}%")
                ->orWhere('short_description', 'like', "%{$s}%")
                ->orWhere('deskripsi', 'like', "%{$s}%")))
            ->when($request->filled('status'), fn ($q) => $q->where('status', $request->query('status')))
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

        $projects = $query->paginate(10)->withQueryString();
        $categories = Category::orderBy('name')->get();
        $technologies = Technology::orderBy('name')->get();

        return view('projects.index', compact('projects', 'categories', 'technologies'));
    }

    /**
     * Menampilkan form tambah proyek baru.
     */
    public function create(): View
    {
        $categories = Category::orderBy('name')->get();
        $technologies = Technology::orderBy('name')->get();

        return view('projects.create', compact('categories', 'technologies'));
    }

    /**
     * Menyimpan data proyek baru ke database.
     */
    public function store(Request $request): RedirectResponse
    {
        $validatedData = $request->validate([
            'judul' => 'required|max:255|unique:projects,judul',
            'category_id' => 'required|exists:categories,id',
            'short_description' => 'nullable|max:255',
            'deskripsi' => 'required|string',
            'project_date' => 'nullable|date',
            'is_featured' => 'nullable|boolean',
            'tautan' => 'nullable|url|max:255',
            'repository_url' => 'nullable|url|max:255',
            'status' => 'required|in:draft,published',
            'thumbnail' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048|dimensions:min_width=400,min_height=300',
            'technologies' => 'nullable|array',
            'technologies.*' => 'exists:technologies,id',
        ], [
            'thumbnail.dimensions' => 'Ukuran thumbnail minimal berdimensi 400x300 px.',
            'thumbnail.max' => 'Ukuran file thumbnail maksimal 2 MB.',
        ]);

        // Buat slug otomatis dari judul proyek
        $validatedData['slug'] = Str::slug($validatedData['judul']);

        $category = Category::find($validatedData['category_id']);
        $validatedData['kategori'] = $category?->name ?? 'Umum';

        // Simpan file thumbnail jika ada
        if ($request->hasFile('thumbnail')) {
            $validatedData['thumbnail'] = $request->file('thumbnail')
                ->store('projects/thumbnails', 'public');
        }

        // Pisahkan data relasi pivot technologies sebelum create
        $technologyIds = $validatedData['technologies'] ?? [];
        unset($validatedData['technologies']);

        $project = Project::create($validatedData);

        // Hubungkan relasi Many-to-Many ke tabel pivot
        if (! empty($technologyIds)) {
            $project->technologies()->sync($technologyIds);
        }

        return redirect()->route('post.projects.index')->with('success', 'Project berhasil ditambahkan.');
    }

    /**
     * Menampilkan detail proyek di admin.
     */
    public function show(Project $project): View
    {
        $project->load(['category', 'technologies', 'images']);

        return view('projects.show', compact('project'));
    }

    /**
     * Menampilkan form edit proyek.
     */
    public function edit(Project $project): View
    {
        $categories = Category::orderBy('name')->get();
        $technologies = Technology::orderBy('name')->get();
        $project->load(['category', 'technologies', 'images']);

        return view('projects.edit', compact('project', 'categories', 'technologies'));
    }

    /**
     * Memperbarui data proyek di database.
     */
    public function update(Request $request, Project $project): RedirectResponse
    {
        $validatedData = $request->validate([
            'judul' => 'required|max:255|unique:projects,judul,'.$project->id,
            'category_id' => 'required|exists:categories,id',
            'short_description' => 'nullable|max:255',
            'deskripsi' => 'required|string',
            'project_date' => 'nullable|date',
            'is_featured' => 'nullable|boolean',
            'tautan' => 'nullable|url|max:255',
            'repository_url' => 'nullable|url|max:255',
            'status' => 'required|in:draft,published',
            'thumbnail' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048|dimensions:min_width=400,min_height=300',
            'technologies' => 'nullable|array',
            'technologies.*' => 'exists:technologies,id',
        ], [
            'thumbnail.dimensions' => 'Ukuran thumbnail minimal berdimensi 400x300 px.',
            'thumbnail.max' => 'Ukuran file thumbnail maksimal 2 MB.',
        ]);

        // Perbarui slug otomatis dari judul proyek
        $validatedData['slug'] = Str::slug($validatedData['judul']);

        $category = Category::find($validatedData['category_id']);
        $validatedData['kategori'] = $category?->name ?? 'Umum';

        // Ganti file thumbnail jika diunggah berkas baru
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
     * Menghapus proyek beserta thumbnail dan galeri fotonya.
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

        return redirect()->route('post.projects.index')->with('success', 'Project berhasil dihapus.');
    }
}

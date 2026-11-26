<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;
use Illuminate\View\View;

class CategoryController extends Controller
{
    /**
     * Menampilkan daftar kategori proyek untuk admin.
     */
    public function index(): View
    {
        $categories = Category::withCount('projects')->orderBy('name')->get();

        return view('categories.index', compact('categories'));
    }

    /**
     * Menyimpan kategori baru ke database dan menghapus cache terkait.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100|unique:categories,name',
        ]);

        Category::create([
            'name' => $validated['name'],
            'slug' => Str::slug($validated['name']),
        ]);

        Cache::forget('portfolio.categories');
        Cache::forget('portfolio.featured');

        return redirect()->route('post.categories.index')->with('success', 'Kategori berhasil ditambahkan.');
    }

    /**
     * Menampilkan form edit kategori.
     */
    public function edit(Category $category): View
    {
        return view('categories.edit', compact('category'));
    }

    /**
     * Memperbarui kategori dan menghapus cache categories serta featured projects.
     */
    public function update(Request $request, Category $category): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100|unique:categories,name,'.$category->id,
        ]);

        $category->update([
            'name' => $validated['name'],
            'slug' => Str::slug($validated['name']),
        ]);

        $category->projects()->update(['kategori' => $validated['name']]);

        Cache::forget('portfolio.categories');
        Cache::forget('portfolio.featured');

        return redirect()->route('post.categories.index')->with('success', 'Kategori berhasil diperbarui.');
    }

    /**
     * Menghapus kategori jika tidak memiliki proyek terkait.
     */
    public function destroy(Category $category): RedirectResponse
    {
        if ($category->projects()->exists()) {
            return redirect()->route('post.categories.index')
                ->with('error', 'Kategori tidak dapat dihapus karena masih digunakan oleh proyek.');
        }

        $category->delete();

        Cache::forget('portfolio.categories');
        Cache::forget('portfolio.featured');

        return redirect()->route('post.categories.index')->with('success', 'Kategori berhasil dihapus.');
    }
}

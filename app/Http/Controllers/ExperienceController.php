<?php

namespace App\Http\Controllers;

use App\Models\Experience;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\View\View;

class ExperienceController extends Controller
{
    /**
     * Menampilkan daftar pengalaman untuk admin.
     */
    public function index(): View
    {
        $experiences = Experience::orderBy('sort_order')->orderByDesc('start_date')->get();

        return view('experiences.index', compact('experiences'));
    }

    /**
     * Menyimpan pengalaman baru dan menghapus cache experiences.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'role' => 'required|string|max:150',
            'organization' => 'required|string|max:150',
            'type' => 'required|string|max:50',
            'location' => 'nullable|string|max:100',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'is_current' => 'nullable|boolean',
            'description' => 'nullable|string',
            'sort_order' => 'nullable|integer|min:0',
        ]);

        $validated['is_current'] = $request->boolean('is_current');
        if ($validated['is_current']) {
            $validated['end_date'] = null;
        }
        $validated['sort_order'] = $validated['sort_order'] ?? 0;

        Experience::create($validated);

        Cache::forget('portfolio.experiences');

        return redirect()->route('post.experiences.index')->with('success', 'Pengalaman berhasil ditambahkan.');
    }

    /**
     * Menampilkan form edit pengalaman.
     */
    public function edit(Experience $experience): View
    {
        return view('experiences.edit', compact('experience'));
    }

    /**
     * Memperbarui data pengalaman dan menghapus cache experiences.
     */
    public function update(Request $request, Experience $experience): RedirectResponse
    {
        $validated = $request->validate([
            'role' => 'required|string|max:150',
            'organization' => 'required|string|max:150',
            'type' => 'required|string|max:50',
            'location' => 'nullable|string|max:100',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'is_current' => 'nullable|boolean',
            'description' => 'nullable|string',
            'sort_order' => 'nullable|integer|min:0',
        ]);

        $validated['is_current'] = $request->boolean('is_current');
        if ($validated['is_current']) {
            $validated['end_date'] = null;
        }
        $validated['sort_order'] = $validated['sort_order'] ?? 0;

        $experience->update($validated);

        Cache::forget('portfolio.experiences');

        return redirect()->route('post.experiences.index')->with('success', 'Pengalaman berhasil diperbarui.');
    }

    /**
     * Menghapus pengalaman dan menghapus cache experiences.
     */
    public function destroy(Experience $experience): RedirectResponse
    {
        $experience->delete();

        Cache::forget('portfolio.experiences');

        return redirect()->route('post.experiences.index')->with('success', 'Pengalaman berhasil dihapus.');
    }
}

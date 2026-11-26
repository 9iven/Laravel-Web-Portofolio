<?php

namespace App\Http\Controllers;

use App\Models\Education;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\View\View;

class EducationController extends Controller
{
    /**
     * Menampilkan daftar riwayat pendidikan untuk admin.
     */
    public function index(): View
    {
        $educations = Education::orderBy('sort_order')->orderByDesc('start_year')->get();

        return view('educations.index', compact('educations'));
    }

    /**
     * Menyimpan riwayat pendidikan baru dan menghapus cache educations.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'institution' => 'required|string|max:150',
            'degree' => 'required|string|max:150',
            'field_of_study' => 'nullable|string|max:150',
            'start_year' => 'required|string|max:20',
            'end_year' => 'nullable|string|max:20',
            'is_current' => 'nullable|boolean',
            'description' => 'nullable|string',
            'sort_order' => 'nullable|integer|min:0',
        ]);

        $validated['is_current'] = $request->boolean('is_current');
        if ($validated['is_current']) {
            $validated['end_year'] = null;
        }
        $validated['sort_order'] = $validated['sort_order'] ?? 0;

        Education::create($validated);

        Cache::forget('portfolio.educations');

        return redirect()->route('post.educations.index')->with('success', 'Riwayat pendidikan berhasil ditambahkan.');
    }

    /**
     * Menampilkan form edit riwayat pendidikan.
     */
    public function edit(Education $education): View
    {
        return view('educations.edit', compact('education'));
    }

    /**
     * Memperbarui riwayat pendidikan dan menghapus cache educations.
     */
    public function update(Request $request, Education $education): RedirectResponse
    {
        $validated = $request->validate([
            'institution' => 'required|string|max:150',
            'degree' => 'required|string|max:150',
            'field_of_study' => 'nullable|string|max:150',
            'start_year' => 'required|string|max:20',
            'end_year' => 'nullable|string|max:20',
            'is_current' => 'nullable|boolean',
            'description' => 'nullable|string',
            'sort_order' => 'nullable|integer|min:0',
        ]);

        $validated['is_current'] = $request->boolean('is_current');
        if ($validated['is_current']) {
            $validated['end_year'] = null;
        }
        $validated['sort_order'] = $validated['sort_order'] ?? 0;

        $education->update($validated);

        Cache::forget('portfolio.educations');

        return redirect()->route('post.educations.index')->with('success', 'Riwayat pendidikan berhasil diperbarui.');
    }

    /**
     * Menghapus riwayat pendidikan dan menghapus cache educations.
     */
    public function destroy(Education $education): RedirectResponse
    {
        $education->delete();

        Cache::forget('portfolio.educations');

        return redirect()->route('post.educations.index')->with('success', 'Riwayat pendidikan berhasil dihapus.');
    }
}

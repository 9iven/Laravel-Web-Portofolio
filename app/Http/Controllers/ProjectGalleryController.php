<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\ProjectImage;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;

class ProjectGalleryController extends Controller
{
    /**
     * Upload multiple images for a project gallery.
     */
    public function store(Request $request, Project $project): RedirectResponse
    {
        $currentCount = $project->images()->count();
        $newFiles = $request->file('images') ?? [];
        $newCount = is_array($newFiles) ? count($newFiles) : 0;

        if ($currentCount + $newCount > 10) {
            throw ValidationException::withMessages([
                'images' => 'Batas maksimal adalah 10 gambar per project. Upload ke-'.($currentCount + $newCount).' ditolak.',
            ]);
        }

        $request->validate([
            'images' => 'required|array|max:10',
            'images.*' => 'image|mimes:jpg,jpeg,png,webp|max:4096|dimensions:min_width=800',
        ], [
            'images.*.dimensions' => 'Setiap gambar galeri wajib memiliki lebar minimal 800 px.',
            'images.*.max' => 'Ukuran setiap gambar maksimal 4 MB.',
        ]);

        foreach ($request->file('images') as $file) {
            $path = $file->store('projects/gallery', 'public');
            $project->images()->create([
                'image' => $path,
                'caption' => null,
                'sort_order' => $project->images()->count() + 1,
            ]);

            // Jika project belum memiliki thumbnail utama, gunakan foto galeri pertama secara otomatis
            if (! $project->thumbnail) {
                $project->update(['thumbnail' => $path]);
            }
        }

        return back()->with('success', 'Gambar galeri berhasil diunggah.');
    }

    /**
     * Update the caption for a specific gallery image.
     */
    public function updateCaption(Request $request, Project $project, ProjectImage $image): RedirectResponse
    {
        abort_if($image->project_id !== $project->id, 404);

        $validated = $request->validate([
            'caption' => 'nullable|string|max:255',
        ]);

        $image->update($validated);

        return back()->with('success', 'Keterangan gambar (caption) berhasil diperbarui.');
    }

    /**
     * Set a specific gallery image as the project's primary thumbnail.
     */
    public function setThumbnail(Project $project, ProjectImage $image): RedirectResponse
    {
        abort_if($image->project_id !== $project->id, 404);

        $project->update(['thumbnail' => $image->image]);

        return back()->with('success', 'Foto galeri berhasil ditetapkan sebagai thumbnail utama proyek.');
    }

    /**
     * Delete a gallery image from storage and database.
     */
    public function destroy(Project $project, ProjectImage $image): RedirectResponse
    {
        abort_if($image->project_id !== $project->id, 404);

        if ($image->image) {
            Storage::disk('public')->delete($image->image);
        }

        $image->delete();

        return back()->with('success', 'Gambar galeri berhasil dihapus.');
    }
}

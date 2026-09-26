<?php

namespace App\Http\Controllers;

use App\Models\Project;
use Illuminate\Http\Request;

class ProjectController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $projects = Project::latest()->get();

        return view('projects.index', compact('projects'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('projects.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'judul' => 'required|min:5|max:200',
            'kategori' => 'required|max:100',
            'deskripsi' => 'required|min:10',
            'teknologi' => 'required|max:255',
            'tautan' => 'nullable|url|max:255',
        ]);

        Project::create($validatedData);

        return redirect()->route('projects.index')->with('success', 'Proyek berhasil ditambahkan!');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $project = Project::findOrFail($id);

        return view('projects.show', compact('project'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $project = Project::findOrFail($id);

        return view('projects.edit', compact('project'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validatedData = $request->validate([
            'judul' => 'required|min:5|max:200',
            'kategori' => 'required|max:100',
            'deskripsi' => 'required|min:10',
            'teknologi' => 'required|max:255',
            'tautan' => 'nullable|url|max:255',
        ]);

        $project = Project::findOrFail($id);
        $project->update($validatedData);

        return redirect()->route('projects.index')->with('success', 'Proyek berhasil diperbarui!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $project = Project::findOrFail($id);
        $project->delete();

        return redirect()->route('projects.index')->with('success', 'Proyek berhasil dihapus!');
    }
}

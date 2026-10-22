<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $perPage = in_array((int) $request->input('per_page'), [5, 10, 25], true)
            ? (int) $request->input('per_page')
            : 10;

        $query = Post::query();

        if ($request->filled('search')) {
            $query->where('title', 'like', '%'.$request->input('search').'%');
        }

        $posts = $query->orderBy('created_at', 'desc')
            ->paginate($perPage)
            ->withQueryString();

        return view('posts.index', compact('posts', 'perPage'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        return view('posts.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validatedData = $request->validate([
            'title' => 'required|string|min:5|max:200',
            'description' => 'required|string|min:10',
            'status' => 'nullable|in:draft,published',
        ]);

        if (empty($validatedData['status'])) {
            $validatedData['status'] = 'draft';
        }

        Post::create($validatedData);

        return redirect()->route('posts.index')->with('success', 'Post berhasil ditambahkan!');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id): View
    {
        $post = Post::findOrFail($id);

        return view('posts.show', compact('post'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id): View
    {
        $post = Post::findOrFail($id);

        return view('posts.edit', compact('post'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id): RedirectResponse
    {
        $validatedData = $request->validate([
            'title' => 'required|string|min:5|max:200',
            'description' => 'required|string|min:10',
            'status' => 'nullable|in:draft,published',
        ]);

        $post = Post::findOrFail($id);

        if (empty($validatedData['status'])) {
            $validatedData['status'] = $post->status ?? 'draft';
        }

        $post->update($validatedData);

        return redirect()->route('posts.index')->with('success', 'Post berhasil diperbarui!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id): RedirectResponse
    {
        $post = Post::findOrFail($id);
        $post->delete();

        return redirect()->route('posts.index')->with('success', 'Post berhasil dihapus!');
    }
}

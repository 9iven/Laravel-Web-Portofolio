<?php

use App\Models\Category;
use App\Models\Project;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->user = User::firstOrCreate(
        ['email' => 'admin@example.com'],
        ['name' => 'Admin', 'password' => bcrypt('password123')]
    );

    $this->category = Category::firstOrCreate(['name' => 'Web Development']);

    $this->project = Project::create([
        'judul' => 'Empirical Test Project',
        'category_id' => $this->category->id,
        'kategori' => 'Web Development',
        'deskripsi' => 'Deskripsi untuk empirical test.',
        'teknologi' => 'Laravel',
        'status' => 'published',
    ]);
});

test('public route /projects is accessible unauthenticated and authenticated', function () {
    // Unauthenticated
    $resGuest = $this->get('/projects');
    $resGuest->assertStatus(200);
    $resGuest->assertSee('Empirical Test Project');

    // Authenticated
    $resAuth = $this->actingAs($this->user)->get('/projects');
    $resAuth->assertStatus(200);
    $resAuth->assertSee('Empirical Test Project');
});

test('public route /projects/daftar aliases to /projects with 302 redirect', function () {
    $response = $this->get('/projects/daftar');
    $response->assertStatus(302);
    $response->assertRedirect(route('projects'));
});

test('public route /projects/{id} is accessible unauthenticated and returns 404 for invalid id', function () {
    // Valid project unauthenticated
    $resGuest = $this->get('/projects/'.$this->project->id);
    $resGuest->assertStatus(200);
    $resGuest->assertSee('Empirical Test Project');

    // Non-existent project
    $res404 = $this->get('/projects/999999');
    $res404->assertStatus(404);
});

test('admin routes under /post require authentication', function () {
    $adminRoutes = [
        ['GET', '/post/dashboard'],
        ['GET', '/post/projects'],
        ['GET', '/post/projects/create'],
        ['POST', '/post/projects'],
        ['GET', '/post/projects/'.$this->project->id.'/edit'],
        ['PUT', '/post/projects/'.$this->project->id],
        ['DELETE', '/post/projects/'.$this->project->id],
    ];

    foreach ($adminRoutes as [$method, $uri]) {
        $response = $this->call($method, $uri);
        $response->assertStatus(302);
        $response->assertRedirect('/login');
    }
});

test('admin routes under /post are accessible when authenticated', function () {
    $resDashboard = $this->actingAs($this->user)->get('/post/dashboard');
    $resDashboard->assertStatus(200);

    $resProjects = $this->actingAs($this->user)->get('/post/projects');
    $resProjects->assertStatus(200);

    $resCreate = $this->actingAs($this->user)->get('/post/projects/create');
    $resCreate->assertStatus(200);
});

test('purged routes like /projects/create or POST /projects respond cleanly without crashing', function () {
    // GET /projects/create -> implicit model binding tries to find Project id 'create' -> 404 Not Found
    $resCreate = $this->get('/projects/create');
    $resCreate->assertStatus(404);

    $resCreateAuth = $this->actingAs($this->user)->get('/projects/create');
    $resCreateAuth->assertStatus(404);

    // POST /projects -> Method Not Allowed
    $resPost = $this->post('/projects', ['judul' => 'Hacker']);
    $resPost->assertStatus(405);
});

test('dashboard route only accepts GET /post/dashboard and rejects unimplemented HTTP methods with 405', function () {
    // Authenticated tests for disallowed methods
    $resPost = $this->actingAs($this->user)->post('/post/dashboard');
    $resPost->assertStatus(405);

    $resPut = $this->actingAs($this->user)->put('/post/dashboard');
    $resPut->assertStatus(405);

    $resDelete = $this->actingAs($this->user)->delete('/post/dashboard');
    $resDelete->assertStatus(405);

    $resPatch = $this->actingAs($this->user)->patch('/post/dashboard');
    $resPatch->assertStatus(405);
});

test('model name attribute mapping works bidirectionally', function () {
    $p = new Project([
        'name' => 'Alias Name Project',
        'category_id' => $this->category->id,
        'kategori' => 'Web Development',
        'deskripsi' => 'Deskripsi alias.',
        'teknologi' => 'Laravel',
    ]);
    $p->save();

    expect($p->judul)->toBe('Alias Name Project');
    expect($p->name)->toBe('Alias Name Project');

    $p->name = 'Updated Alias Name';
    $p->save();

    expect($p->judul)->toBe('Updated Alias Name');
    expect($p->name)->toBe('Updated Alias Name');
});

test('view compatibility layers render correctly', function () {
    // Test posts.dashboard view
    $viewDashboard = view('posts.dashboard', [
        'totalProjects' => 1,
        'totalPublished' => 1,
        'totalDraft' => 0,
        'totalCategories' => 1,
        'totalTechnologies' => 0,
        'totalSkills' => 0,
        'categoriesWithCount' => collect(),
        'topSkills' => collect(),
        'latestProjects' => collect([$this->project]),
    ])->render();

    expect($viewDashboard)->toContain('Dashboard Admin');

    // Test portofolio.project view
    $viewProject = view('portofolio.project', [
        'project' => $this->project,
    ])->render();

    expect($viewProject)->toContain('Empirical Test Project');
});

test('non-image files are rejected for thumbnail and gallery uploads', function () {
    $maliciousFile = UploadedFile::fake()->create('exploit.php', 100, 'text/x-php');

    // Thumbnail upload rejection
    $resThumb = $this->actingAs($this->user)->post(route('post.projects.store'), [
        'judul' => 'Exploit Project',
        'category_id' => $this->category->id,
        'deskripsi' => 'Deskripsi proyek exploit.',
        'teknologi' => 'PHP',
        'thumbnail' => $maliciousFile,
    ]);
    $resThumb->assertSessionHasErrors('thumbnail');

    // Gallery upload rejection
    $resGallery = $this->actingAs($this->user)->post(route('post.projects.gallery.store', $this->project->id), [
        'images' => [$maliciousFile],
    ]);
    $resGallery->assertSessionHasErrors('images.0');
});

test('gallery controller prevents IDOR access across different projects', function () {
    $otherProject = Project::create([
        'judul' => 'Other Project',
        'category_id' => $this->category->id,
        'kategori' => 'Web Development',
        'deskripsi' => 'Deskripsi proyek lain.',
        'teknologi' => 'Laravel',
    ]);

    $imageOfOther = $otherProject->images()->create([
        'image' => 'projects/gallery/other.jpg',
        'caption' => 'Other Caption',
    ]);

    // Attempt to update caption of otherProject image through this->project URL -> 404
    $resCaption = $this->actingAs($this->user)->put(
        route('post.projects.gallery.caption', [$this->project->id, $imageOfOther->id]),
        ['caption' => 'Hijacked Caption']
    );
    $resCaption->assertStatus(404);

    // Attempt to set thumbnail of otherProject image on this->project -> 404
    $resThumb = $this->actingAs($this->user)->post(
        route('post.projects.gallery.set-thumbnail', [$this->project->id, $imageOfOther->id])
    );
    $resThumb->assertStatus(404);

    // Attempt to delete otherProject image through this->project URL -> 404
    $resDelete = $this->actingAs($this->user)->delete(
        route('post.projects.gallery.destroy', [$this->project->id, $imageOfOther->id])
    );
    $resDelete->assertStatus(404);
});

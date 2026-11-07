<?php

use App\Models\Category;
use App\Models\Project;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

uses(RefreshDatabase::class);

function createTestPng(string $filename, int $width, int $height): UploadedFile
{
    $ihdr = pack('NNCCCCC', $width, $height, 8, 2, 0, 0, 0);
    $crc = crc32('IHDR'.$ihdr);
    $content = "\x89PNG\r\n\x1a\n\x00\x00\x00\x0dIHDR".$ihdr.pack('N', $crc);

    return UploadedFile::fake()->createWithContent($filename, $content);
}

beforeEach(function () {
    Storage::fake('public');

    $this->user = User::firstOrCreate(
        ['email' => 'admin@example.com'],
        ['name' => 'Admin Test', 'password' => bcrypt('password123')]
    );

    $this->category = Category::firstOrCreate(['name' => 'Web Development']);
});

test('app layout container uses max-w-5xl', function () {
    $layoutContent = file_get_contents(resource_path('views/layouts/app.blade.php'));
    expect($layoutContent)->toContain('max-w-5xl');
    expect($layoutContent)->not->toContain('max-w-2xl');
});

test('navbar links correctly to canonical projects route and active state', function () {
    $navbarContent = file_get_contents(resource_path('views/partials/navbar.blade.php'));
    expect($navbarContent)->toContain("route('projects')");
    expect($navbarContent)->toContain("request()->routeIs('projects*')");

    $navContent = file_get_contents(resource_path('views/layouts/navigation.blade.php'));
    expect($navContent)->toContain("route('projects')");
    expect($navContent)->toContain("request()->routeIs('projects*')");
});

test('stat-card component renders with label, value, and custom color', function () {
    $rendered = (string) $this->blade('<x-stat-card label="Total Views" value="1,250" color="text-emerald-600" />');

    expect($rendered)->toContain('Total Views');
    expect($rendered)->toContain('1,250');
    expect($rendered)->toContain('text-emerald-600');
    expect($rendered)->toContain('rounded-xl');
});

test('projects.show renders cleanly with full data including thumbnail and gallery', function () {
    $project = Project::create([
        'judul' => 'Full Showcase Project',
        'category_id' => $this->category->id,
        'kategori' => 'Web Development',
        'deskripsi' => 'Deskripsi proyek lengkap dengan galeri foto dan thumbnail.',
        'teknologi' => 'Laravel, Tailwind CSS, Alpine.js',
        'tautan' => 'https://example.com/demo',
        'status' => 'published',
        'thumbnail' => 'projects/thumbnails/thumb_showcase.jpg',
    ]);

    $img1 = $project->images()->create([
        'image' => 'projects/gallery/gal_1.jpg',
        'caption' => 'Screenshot Halaman Utama',
        'sort_order' => 1,
    ]);

    $img2 = $project->images()->create([
        'image' => 'projects/gallery/gal_2.jpg',
        'caption' => null,
        'sort_order' => 2,
    ]);

    $response = $this->get(route('projects.show', $project->id));
    $response->assertStatus(200);
    $response->assertSee('Full Showcase Project');
    $response->assertSee('Web Development');
    $response->assertSee('Laravel, Tailwind CSS, Alpine.js');
    $response->assertSee('https://example.com/demo');
    $response->assertSee('Screenshot Halaman Utama');
    $response->assertSee('2 Dokumentasi Foto');
    $response->assertSee('storage/projects/thumbnails/thumb_showcase.jpg');
    $response->assertSee('storage/projects/gallery/gal_1.jpg');
});

test('projects.show renders without errors when project has no thumbnail and no gallery images', function () {
    $project = Project::create([
        'judul' => 'Bare Project',
        'category_id' => $this->category->id,
        'kategori' => 'Mobile',
        'deskripsi' => 'Deskripsi proyek tanpa gambar sama sekali.',
        'teknologi' => 'Flutter',
        'status' => 'draft',
        'thumbnail' => null,
    ]);

    $response = $this->get(route('projects.show', $project->id));
    $response->assertStatus(200);
    $response->assertSee('Bare Project');
    $response->assertSee('Draft');
    $response->assertDontSee('Galeri Foto Proyek');
    $response->assertDontSee('Dokumentasi Foto');
});

test('projects.show falls back to first gallery image when project thumbnail is null', function () {
    $project = Project::create([
        'judul' => 'Auto Thumb Project',
        'category_id' => $this->category->id,
        'kategori' => 'Backend',
        'deskripsi' => 'Deskripsi proyek tanpa thumbnail langsung.',
        'teknologi' => 'Golang',
        'thumbnail' => null,
    ]);

    $project->images()->create([
        'image' => 'projects/gallery/first_gal.png',
        'caption' => 'Foto Pertama',
        'sort_order' => 1,
    ]);

    $response = $this->get(route('projects.show', $project->id));
    $response->assertStatus(200);
    $response->assertSee('storage/projects/gallery/first_gal.png');
});

test('projects.show handles category fallback when category relation is null', function () {
    $project = Project::create([
        'judul' => 'Orphan Category Project',
        'category_id' => null,
        'kategori' => 'Legacy Kategori Fallback',
        'deskripsi' => 'Deskripsi proyek tanpa relasi category_id.',
        'teknologi' => 'PHP Native',
    ]);

    $response = $this->get(route('projects.show', $project->id));
    $response->assertStatus(200);
    $response->assertSee('Legacy Kategori Fallback');
});

test('projects.show escapes XSS characters in judul and caption safely', function () {
    $xssTitle = 'Project <script>alert("XSS-TITLE")</script>';
    $xssCaption = 'Caption <img src=x onerror=alert("XSS-CAPTION")>';

    $project = Project::create([
        'judul' => $xssTitle,
        'category_id' => $this->category->id,
        'kategori' => 'Security',
        'deskripsi' => 'Deskripsi dengan aman.',
        'teknologi' => 'Security',
    ]);

    $project->images()->create([
        'image' => 'projects/gallery/xss.jpg',
        'caption' => $xssCaption,
    ]);

    $response = $this->get(route('projects.show', $project->id));
    $response->assertStatus(200);
    $response->assertDontSee('<script>alert("XSS-TITLE")</script>', false);
    $response->assertDontSee('<img src=x onerror=alert("XSS-CAPTION")>', false);
    $response->assertSee(e($xssTitle), false);
    $response->assertSee(e($xssCaption), false);
});

test('projects.edit renders with thumbnail indicators and gallery management actions', function () {
    $project = Project::create([
        'judul' => 'Edit Mode Project',
        'category_id' => $this->category->id,
        'kategori' => 'Web Development',
        'deskripsi' => 'Deskripsi untuk halaman edit.',
        'teknologi' => 'Vue',
        'thumbnail' => 'projects/gallery/active_thumb.jpg',
    ]);

    $img1 = $project->images()->create([
        'image' => 'projects/gallery/active_thumb.jpg',
        'caption' => 'Ini adalah thumbnail aktif',
        'sort_order' => 1,
    ]);

    $img2 = $project->images()->create([
        'image' => 'projects/gallery/other_thumb.jpg',
        'caption' => 'Foto lain',
        'sort_order' => 2,
    ]);

    $response = $this->actingAs($this->user)->get(route('post.projects.edit', $project->id));
    $response->assertStatus(200);
    $response->assertSee('Edit Proyek');
    $response->assertSee('★ Thumbnail Utama');
    $response->assertSee('Thumbnail Aktif');
    $response->assertSee('Jadikan Thumbnail');
    $response->assertSee('Hapus');
    $response->assertSee('2 / 10 Foto');
    $response->assertSee('+ Unggah ke Galeri');
});

test('projects.edit displays limit reached notice when project reaches 10 images', function () {
    $project = Project::create([
        'judul' => 'Max Limit Project',
        'category_id' => $this->category->id,
        'kategori' => 'Web Development',
        'deskripsi' => 'Deskripsi untuk batas maksimal.',
        'teknologi' => 'Svelte',
    ]);

    for ($i = 1; $i <= 10; $i++) {
        $project->images()->create([
            'image' => "projects/gallery/img_{$i}.jpg",
            'caption' => "Foto {$i}",
            'sort_order' => $i,
        ]);
    }

    $response = $this->actingAs($this->user)->get(route('post.projects.edit', $project->id));
    $response->assertStatus(200);
    $response->assertSee('10 / 10 Foto');
    $response->assertSee('Proyek ini telah mencapai batas maksimal 10 foto galeri');
    $response->assertDontSee('+ Unggah ke Galeri');
});

test('projects.create and partial _form render correctly with empty model and category options', function () {
    $response = $this->actingAs($this->user)->get(route('post.projects.create'));
    $response->assertStatus(200);
    $response->assertSee('Tambah Proyek Baru');
    $response->assertSee('name="judul"', false);
    $response->assertSee('name="category_id"', false);
    $response->assertSee('name="status"', false);
    $response->assertSee('name="thumbnail"', false);
    $response->assertSee('name="teknologi"', false);
    $response->assertSee('name="tautan"', false);
    $response->assertSee('name="deskripsi"', false);
    $response->assertSee($this->category->name);
});

test('projects.index renders projects grid and empty state cleanly', function () {
    Project::query()->delete();

    // 1. Empty state
    $resEmpty = $this->get(route('projects'));
    $resEmpty->assertStatus(200);
    $resEmpty->assertSee('Belum ada data proyek di database');

    // 2. Populated list
    $p = Project::create([
        'judul' => 'Grid Test Project',
        'category_id' => $this->category->id,
        'kategori' => 'Web Development',
        'deskripsi' => 'Deskripsi singkat.',
        'teknologi' => 'Laravel',
        'status' => 'published',
    ]);

    $resPopulated = $this->get(route('projects'));
    $resPopulated->assertStatus(200);
    $resPopulated->assertSee('Grid Test Project');
    $resPopulated->assertSee('Tidak ada thumbnail');
    $resPopulated->assertSee('Lihat Detail');
});

test('full form submission lifecycle validates exact field names', function () {
    // 1. Submit store form
    $thumbFile = createTestPng('new_thumb.png', 500, 400);

    $storePayload = [
        'judul' => 'Lifecycle Project Test',
        'category_id' => $this->category->id,
        'status' => 'published',
        'thumbnail' => $thumbFile,
        'teknologi' => 'Tailwind, PHP 8.5',
        'tautan' => 'https://github.com/example/lifecycle',
        'deskripsi' => 'Deskripsi lifecycle yang memenuhi panjang minimal.',
    ];

    $resStore = $this->actingAs($this->user)->post(route('post.projects.store'), $storePayload);
    $resStore->assertRedirect(route('post.projects.index'));

    $createdProject = Project::where('judul', 'Lifecycle Project Test')->first();
    expect($createdProject)->not->toBeNull();
    expect($createdProject->category_id)->toBe($this->category->id);
    expect($createdProject->status)->toBe('published');
    expect($createdProject->teknologi)->toBe('Tailwind, PHP 8.5');
    expect($createdProject->tautan)->toBe('https://github.com/example/lifecycle');
    expect($createdProject->thumbnail)->not->toBeNull();

    // 2. Submit update form
    $updatePayload = [
        'judul' => 'Updated Lifecycle Project',
        'category_id' => $this->category->id,
        'status' => 'draft',
        'teknologi' => 'Laravel 12, Tailwind CSS v4',
        'tautan' => 'https://github.com/example/updated',
        'deskripsi' => 'Deskripsi yang diperbarui dan tetap valid.',
    ];

    $resUpdate = $this->actingAs($this->user)->put(route('post.projects.update', $createdProject->id), $updatePayload);
    $resUpdate->assertRedirect(route('post.projects.index'));

    $createdProject->refresh();
    expect($createdProject->judul)->toBe('Updated Lifecycle Project');
    expect($createdProject->status)->toBe('draft');
    expect($createdProject->teknologi)->toBe('Laravel 12, Tailwind CSS v4');

    // 3. Upload gallery images[]
    $gal1 = createTestPng('gal_life1.png', 900, 600);
    $gal2 = createTestPng('gal_life2.png', 900, 600);

    $resGallery = $this->actingAs($this->user)->post(route('post.projects.gallery.store', $createdProject->id), [
        'images' => [$gal1, $gal2],
    ]);
    $resGallery->assertSessionHasNoErrors();
    expect($createdProject->images()->count())->toBe(2);

    $firstImg = $createdProject->images()->first();

    // 4. Update caption
    $resCaption = $this->actingAs($this->user)->put(
        route('post.projects.gallery.caption', [$createdProject->id, $firstImg->id]),
        ['caption' => 'Updated Gallery Caption']
    );
    $resCaption->assertSessionHasNoErrors();
    $firstImg->refresh();
    expect($firstImg->caption)->toBe('Updated Gallery Caption');

    // 5. Set as thumbnail
    $resThumb = $this->actingAs($this->user)->post(
        route('post.projects.gallery.set-thumbnail', [$createdProject->id, $firstImg->id])
    );
    $resThumb->assertSessionHasNoErrors();
    $createdProject->refresh();
    expect($createdProject->thumbnail)->toBe($firstImg->image);
});

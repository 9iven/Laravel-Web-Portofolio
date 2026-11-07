<?php

use App\Models\Category;
use App\Models\Project;
use App\Models\ProjectImage;
use App\Models\Skill;
use App\Models\Technology;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

uses(RefreshDatabase::class);

function createFakePng(string $filename, int $width, int $height): UploadedFile
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
        ['name' => 'Admin', 'password' => bcrypt('password123')]
    );

    $this->category = Category::firstOrCreate(['name' => 'Web Development']);
    Technology::firstOrCreate(['name' => 'Laravel 12']);
    Skill::firstOrCreate(['name' => 'Laravel', 'level' => 90]);
});

test('dashboard displays correct statistics, withCount, and published plus draft equals total projects', function () {
    Project::query()->delete();

    Project::create([
        'judul' => 'Project 1',
        'category_id' => $this->category->id,
        'kategori' => 'Web Development',
        'deskripsi' => 'Deskripsi proyek pengujian satu.',
        'teknologi' => 'Laravel',
        'status' => 'published',
    ]);

    Project::create([
        'judul' => 'Project 2',
        'category_id' => $this->category->id,
        'kategori' => 'Web Development',
        'deskripsi' => 'Deskripsi proyek pengujian dua.',
        'teknologi' => 'Vue',
        'status' => 'draft',
    ]);

    $response = $this->actingAs($this->user)->get(route('post.dashboard.index'));

    $response->assertStatus(200);
    $response->assertSee('Dashboard Admin');
    $response->assertSee('Total Projects');
    $response->assertSee('Published');
    $response->assertSee('Draft');

    $total = Project::count();
    $published = Project::where('status', 'published')->count();
    $draft = Project::where('status', 'draft')->count();

    expect($published + $draft)->toBe($total);
    expect($total)->toBe(2);
    expect($published)->toBe(1);
    expect($draft)->toBe(1);
});

test('thumbnail can be uploaded and invalid dimensions are rejected', function () {
    // 1. Gambar terlalu kecil (200x200 < 400x300) harus ditolak
    $smallImage = createFakePng('small.png', 200, 200);

    $resFail = $this->actingAs($this->user)->post(route('post.projects.store'), [
        'judul' => 'Proyek Baru',
        'category_id' => $this->category->id,
        'deskripsi' => 'Deskripsi lengkap proyek uji coba.',
        'teknologi' => 'Laravel, MySQL',
        'thumbnail' => $smallImage,
    ]);

    $resFail->assertSessionHasErrors('thumbnail');

    // 2. Gambar valid (600x400 >= 400x300) harus berhasil disimpan
    $validImage = createFakePng('thumbnail.png', 600, 400);

    $resSuccess = $this->actingAs($this->user)->post(route('post.projects.store'), [
        'judul' => 'Proyek Berhasil',
        'category_id' => $this->category->id,
        'deskripsi' => 'Deskripsi lengkap proyek valid.',
        'teknologi' => 'Laravel, Tailwind',
        'thumbnail' => $validImage,
    ]);

    $resSuccess->assertRedirect(route('post.projects.index'));

    $project = Project::where('judul', 'Proyek Berhasil')->first();
    expect($project)->not->toBeNull();
    expect($project->thumbnail)->not->toBeNull();

    Storage::disk('public')->assertExists($project->thumbnail);
});

test('updating thumbnail replaces and deletes old file from storage', function () {
    $firstImage = createFakePng('first.png', 600, 400);

    $this->actingAs($this->user)->post(route('post.projects.store'), [
        'judul' => 'Proyek Ganti Gambar',
        'category_id' => $this->category->id,
        'deskripsi' => 'Deskripsi lengkap proyek ganti gambar.',
        'teknologi' => 'Laravel',
        'thumbnail' => $firstImage,
    ]);

    $project = Project::where('judul', 'Proyek Ganti Gambar')->first();
    $oldPath = $project->thumbnail;
    Storage::disk('public')->assertExists($oldPath);

    // Ganti thumbnail dengan file baru
    $secondImage = createFakePng('second.png', 600, 400);

    $this->actingAs($this->user)->put(route('post.projects.update', $project->id), [
        'judul' => 'Proyek Ganti Gambar Updated',
        'category_id' => $this->category->id,
        'deskripsi' => 'Deskripsi lengkap proyek ganti gambar updated.',
        'teknologi' => 'Laravel',
        'thumbnail' => $secondImage,
    ]);

    $project->refresh();
    expect($project->thumbnail)->not->toBe($oldPath);
    Storage::disk('public')->assertMissing($oldPath);
    Storage::disk('public')->assertExists($project->thumbnail);
});

test('gallery rejects images under 800px width and enforces 10 images limit', function () {
    $project = Project::create([
        'judul' => 'Proyek Galeri Test',
        'category_id' => $this->category->id,
        'kategori' => 'Web Development',
        'deskripsi' => 'Deskripsi lengkap proyek galeri.',
        'teknologi' => 'Laravel',
    ]);

    // 1. Gambar lebar 500 px ditolak (minimal 800 px)
    $narrowImage = createFakePng('narrow.png', 500, 600);

    $resNarrow = $this->actingAs($this->user)->post(route('post.projects.gallery.store', $project->id), [
        'images' => [$narrowImage],
    ]);

    $resNarrow->assertSessionHasErrors('images.0');

    // 2. Upload valid 3 gambar (lebar 1000px >= 800px)
    $validImages = [
        createFakePng('img1.png', 1000, 700),
        createFakePng('img2.png', 1000, 700),
        createFakePng('img3.png', 1000, 700),
    ];

    $resValid = $this->actingAs($this->user)->post(route('post.projects.gallery.store', $project->id), [
        'images' => $validImages,
    ]);

    $resValid->assertSessionHasNoErrors();
    expect($project->images()->count())->toBe(3);

    // 3. Tambah 7 gambar lagi sehingga mencapai 10 gambar
    for ($i = 4; $i <= 10; $i++) {
        $project->images()->create([
            'image' => "projects/gallery/mock_{$i}.jpg",
            'caption' => "Caption {$i}",
        ]);
    }
    expect($project->images()->count())->toBe(10);

    // 4. Upload gambar ke-11 harus ditolak
    $eleventhImage = createFakePng('img11.png', 1000, 700);

    $resEleventh = $this->actingAs($this->user)->post(route('post.projects.gallery.store', $project->id), [
        'images' => [$eleventhImage],
    ]);

    $resEleventh->assertSessionHasErrors('images');
});

test('gallery caption can be updated via PUT and displays on public project page', function () {
    $project = Project::create([
        'judul' => 'Proyek Caption Test',
        'category_id' => $this->category->id,
        'kategori' => 'Web Development',
        'deskripsi' => 'Deskripsi lengkap proyek caption.',
        'teknologi' => 'Laravel',
    ]);

    $image = $project->images()->create([
        'image' => 'projects/gallery/mock_caption.jpg',
        'caption' => null,
    ]);

    // Update caption dengan route PUT
    $resPut = $this->actingAs($this->user)->put(route('post.projects.gallery.caption', [$project->id, $image->id]), [
        'caption' => 'Tampilan Dashboard Antarmuka Pengguna',
    ]);

    $resPut->assertSessionHasNoErrors();
    $image->refresh();
    expect($image->caption)->toBe('Tampilan Dashboard Antarmuka Pengguna');

    // Buka halaman publik project
    $resPublic = $this->get(route('projects.show', $project->id));
    $resPublic->assertStatus(200);
    $resPublic->assertSee('Tampilan Dashboard Antarmuka Pengguna');
});

test('gallery upload automatically sets thumbnail when project has none and can be set manually', function () {
    $project = Project::create([
        'judul' => 'Proyek Auto Thumbnail',
        'category_id' => $this->category->id,
        'kategori' => 'Web Development',
        'deskripsi' => 'Deskripsi proyek auto thumbnail.',
        'teknologi' => 'Laravel',
        'thumbnail' => null,
    ]);

    $validImage = createFakePng('auto_thumb.png', 900, 600);

    $this->actingAs($this->user)->post(route('post.projects.gallery.store', $project->id), [
        'images' => [$validImage],
    ]);

    $project->refresh();
    expect($project->thumbnail)->not->toBeNull();
    expect($project->thumbnail_url)->not->toBeNull();

    // Buat gambar kedua dan jadikan thumbnail utama
    $secondImage = $project->images()->create([
        'image' => 'projects/gallery/second_manual.jpg',
        'caption' => 'Gambar kedua',
    ]);

    $this->actingAs($this->user)->post(route('post.projects.gallery.set-thumbnail', [$project->id, $secondImage->id]));

    $project->refresh();
    expect($project->thumbnail)->toBe('projects/gallery/second_manual.jpg');
});

test('destroying project removes thumbnail and all gallery images from storage', function () {
    $project = Project::create([
        'judul' => 'Proyek Hapus Bersih',
        'category_id' => $this->category->id,
        'kategori' => 'Web Development',
        'deskripsi' => 'Deskripsi lengkap proyek hapus bersih.',
        'teknologi' => 'Laravel',
        'thumbnail' => 'projects/thumbnails/thumb_del.jpg',
    ]);

    Storage::disk('public')->put('projects/thumbnails/thumb_del.jpg', 'fake-bytes');

    $galleryImage = $project->images()->create([
        'image' => 'projects/gallery/gal_del.jpg',
        'caption' => 'Galeri yang akan dihapus',
    ]);
    Storage::disk('public')->put('projects/gallery/gal_del.jpg', 'fake-bytes');

    Storage::disk('public')->assertExists('projects/thumbnails/thumb_del.jpg');
    Storage::disk('public')->assertExists('projects/gallery/gal_del.jpg');

    $resDelete = $this->actingAs($this->user)->delete(route('post.projects.destroy', $project->id));
    $resDelete->assertRedirect(route('post.projects.index'));

    Storage::disk('public')->assertMissing('projects/thumbnails/thumb_del.jpg');
    Storage::disk('public')->assertMissing('projects/gallery/gal_del.jpg');
    expect(Project::find($project->id))->toBeNull();
    expect(ProjectImage::find($galleryImage->id))->toBeNull();
});

test('skill management allows creating and updating skills with validation', function () {
    $resCreate = $this->actingAs($this->user)->post(route('post.skills.store'), [
        'name' => 'Docker & DevOps',
        'level' => 85,
    ]);
    $resCreate->assertRedirect(route('post.skills.index'));

    $skill = Skill::where('name', 'Docker & DevOps')->first();
    expect($skill)->not->toBeNull();
    expect($skill->level)->toBe(85);

    // Update skill
    $resUpdate = $this->actingAs($this->user)->put(route('post.skills.update', $skill->id), [
        'name' => 'Docker & Kubernetes',
        'level' => 95,
    ]);
    $resUpdate->assertRedirect(route('post.skills.index'));

    $skill->refresh();
    expect($skill->name)->toBe('Docker & Kubernetes');
    expect($skill->level)->toBe(95);
});

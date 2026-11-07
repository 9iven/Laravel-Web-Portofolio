<?php

use App\Models\Category;
use App\Models\Project;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\MessageBag;
use Illuminate\Support\ViewErrorBag;

uses(RefreshDatabase::class);

beforeEach(function () {
    Storage::fake('public');

    $this->user = User::firstOrCreate(
        ['email' => 'admin@example.com'],
        ['name' => 'Admin', 'password' => bcrypt('password123')]
    );

    $this->category = Category::firstOrCreate(['name' => 'Web Development']);

    $this->project = Project::create([
        'judul' => 'Empirical Layout Test Project',
        'category_id' => $this->category->id,
        'kategori' => 'Web Development',
        'deskripsi' => 'Deskripsi proyek untuk stress testing antarmuka.',
        'teknologi' => 'Laravel, Tailwind, Alpine.js',
        'status' => 'published',
    ]);
});

test('form renders validation error bags cleanly beneath inputs without breaking 2-column grid', function () {
    // 1. Submit request with invalid/missing values to trigger validation errors
    $response = $this->actingAs($this->user)->post(route('post.projects.store'), [
        'status' => 'invalid_status',
    ]);
    $response->assertSessionHasErrors(['judul', 'category_id', 'status', 'deskripsi', 'teknologi']);

    // 2. Render create view with simulated comprehensive ViewErrorBag
    $errors = new ViewErrorBag;
    $bag = new MessageBag([
        'judul' => ['Judul proyek wajib diisi.'],
        'category_id' => ['Kategori wajib dipilih.'],
        'status' => ['Status publikasi tidak valid.'],
        'thumbnail' => ['Ukuran thumbnail minimal 400x300 piksel.'],
        'teknologi' => ['Teknologi yang digunakan wajib diisi.'],
        'tautan' => ['Format URL repositori tidak valid.'],
        'deskripsi' => ['Deskripsi proyek wajib diisi.'],
    ]);
    $errors->put('default', $bag);

    $html = view('projects.create', [
        'categories' => Category::all(),
        'errors' => $errors,
    ])->render();

    // Verify all error messages are present
    expect($html)->toContain('Judul proyek wajib diisi.');
    expect($html)->toContain('Kategori wajib dipilih.');
    expect($html)->toContain('Status publikasi tidak valid.');
    expect($html)->toContain('Ukuran thumbnail minimal 400x300 piksel.');
    expect($html)->toContain('Teknologi yang digunakan wajib diisi.');
    expect($html)->toContain('Format URL repositori tidak valid.');
    expect($html)->toContain('Deskripsi proyek wajib diisi.');

    // 3. Inspect DOM structural integrity using DOMDocument
    $dom = new DOMDocument;
    // suppress HTML5 tag warnings
    @$dom->loadHTML('<?xml encoding="utf-8" ?>'.$html);
    $xpath = new DOMXPath($dom);

    // Find the 2-column grids (grid grid-cols-1 sm:grid-cols-2 gap-4)
    $grids = $xpath->query("//div[contains(@class, 'grid grid-cols-1 sm:grid-cols-2 gap-4')]");
    expect($grids->length)->toBe(2);

    // Grid 1: Category & Status
    $grid1Children = [];
    foreach ($grids->item(0)->childNodes as $child) {
        if ($child->nodeType === XML_ELEMENT_NODE) {
            $grid1Children[] = $child;
        }
    }
    // Must strictly have exactly 2 child columns so grid structure does not break!
    expect(count($grid1Children))->toBe(2);

    // First column: category select and its error paragraph
    $col1Errors = $xpath->query(".//p[contains(@class, 'text-rose-600')]", $grid1Children[0]);
    expect($col1Errors->length)->toBe(1);
    expect($col1Errors->item(0)->textContent)->toBe('Kategori wajib dipilih.');

    // Second column: status select and its error paragraph
    $col2Errors = $xpath->query(".//p[contains(@class, 'text-rose-600')]", $grid1Children[1]);
    expect($col2Errors->length)->toBe(1);
    expect($col2Errors->item(0)->textContent)->toBe('Status publikasi tidak valid.');

    // Grid 2: Teknologi & Tautan
    $grid2Children = [];
    foreach ($grids->item(1)->childNodes as $child) {
        if ($child->nodeType === XML_ELEMENT_NODE) {
            $grid2Children[] = $child;
        }
    }
    expect(count($grid2Children))->toBe(2);

    $grid2Col1Errors = $xpath->query(".//p[contains(@class, 'text-rose-600')]", $grid2Children[0]);
    expect($grid2Col1Errors->length)->toBe(1);
    expect($grid2Col1Errors->item(0)->textContent)->toBe('Teknologi yang digunakan wajib diisi.');

    $grid2Col2Errors = $xpath->query(".//p[contains(@class, 'text-rose-600')]", $grid2Children[1]);
    expect($grid2Col2Errors->length)->toBe(1);
    expect($grid2Col2Errors->item(0)->textContent)->toBe('Format URL repositori tidak valid.');
});

test('gallery edge case: 0 images renders clean empty state in admin and hides public gallery block', function () {
    expect($this->project->images()->count())->toBe(0);

    // 1. Admin Edit View
    $resAdmin = $this->actingAs($this->user)->get(route('post.projects.edit', $this->project->id));
    $resAdmin->assertStatus(200);

    // Badge shows 0 / 10 Foto
    $resAdmin->assertSee('0 / 10 Foto');

    // Empty state message displayed
    $resAdmin->assertSee('Belum ada foto di galeri proyek ini.');

    // Upload form is available
    $resAdmin->assertSee('+ Unggah ke Galeri');
    $resAdmin->assertSee('Unggah Foto Galeri');
    $resAdmin->assertDontSee('Proyek ini telah mencapai batas maksimal 10 foto galeri.');

    // 2. Public Detail View
    $resPublic = $this->get(route('projects.show', $this->project->id));
    $resPublic->assertStatus(200);

    // Public gallery section is cleanly omitted when 0 images exist
    $resPublic->assertDontSee('Galeri Foto Proyek');
    $resPublic->assertDontSee('Dokumentasi Foto');
});

test('gallery edge case: 1 image renders correctly with aspect ratio and operational actions', function () {
    $this->project->images()->create([
        'image' => 'projects/gallery/single.jpg',
        'caption' => 'Foto Dokumentasi Tunggal',
        'sort_order' => 1,
    ]);

    expect($this->project->images()->count())->toBe(1);

    // 1. Admin Edit View
    $resAdmin = $this->actingAs($this->user)->get(route('post.projects.edit', $this->project->id));
    $resAdmin->assertStatus(200);

    $resAdmin->assertSee('1 / 10 Foto');
    $resAdmin->assertSee('Foto Dokumentasi Tunggal');
    $resAdmin->assertSee('Jadikan Thumbnail');
    $resAdmin->assertSee('Hapus');
    $resAdmin->assertDontSee('Belum ada foto di galeri proyek ini.');
    $resAdmin->assertSee('+ Unggah ke Galeri');

    // 2. Public Detail View
    $resPublic = $this->get(route('projects.show', $this->project->id));
    $resPublic->assertStatus(200);

    $resPublic->assertSee('Galeri Foto Proyek');
    $resPublic->assertSee('1 Dokumentasi Foto');
    $resPublic->assertSee('Foto Dokumentasi Tunggal');
    $resPublic->assertSee('projects/gallery/single.jpg');
});

test('gallery edge case: exactly 10 images enforces limit state and prevents further uploads in admin UI', function () {
    for ($i = 1; $i <= 10; $i++) {
        $this->project->images()->create([
            'image' => "projects/gallery/photo_{$i}.jpg",
            'caption' => "Dokumentasi foto ke-{$i}",
            'sort_order' => $i,
        ]);
    }

    expect($this->project->images()->count())->toBe(10);

    // 1. Admin Edit View
    $resAdmin = $this->actingAs($this->user)->get(route('post.projects.edit', $this->project->id));
    $resAdmin->assertStatus(200);

    // Counter badge at max limit with danger rose color
    $resAdmin->assertSee('10 / 10 Foto');
    $resAdmin->assertSee('bg-rose-100 text-rose-700');

    // Upload form must be omitted
    $resAdmin->assertDontSee('+ Unggah ke Galeri');
    $resAdmin->assertDontSee('name="images[]"', false);

    // Limit warning notice must be shown
    $resAdmin->assertSee('Proyek ini telah mencapai batas maksimal 10 foto galeri. Hapus foto lama jika ingin menambah yang baru.');

    // 2. Public Detail View
    $resPublic = $this->get(route('projects.show', $this->project->id));
    $resPublic->assertStatus(200);

    $resPublic->assertSee('Galeri Foto Proyek');
    $resPublic->assertSee('10 Dokumentasi Foto');
    for ($i = 1; $i <= 10; $i++) {
        $resPublic->assertSee("Dokumentasi foto ke-{$i}");
    }
});

test('gallery edge case: long captions render cleanly without layout overflow or breaking card bounds', function () {
    $longCaption = 'Dokumentasi arsitektur sistem berbasis cloud & microservices dengan skalabilitas tinggi serta load balancing terdistribusi 2026. '.
                   'Memuat teks panjang berulang-ulang untuk memastikan bahwa card container tidak mengalami truncation yang terpotong secara visual '.
                   'atau merusak layout kartu galeri di tampilan publik maupun admin: <script>console.log("safe");</script> "quotes" & symbols.';

    $image = $this->project->images()->create([
        'image' => 'projects/gallery/long_caption.jpg',
        'caption' => $longCaption,
        'sort_order' => 1,
    ]);

    // 1. Admin Edit View
    $resAdmin = $this->actingAs($this->user)->get(route('post.projects.edit', $this->project->id));
    $resAdmin->assertStatus(200);
    // Long caption must be safely populated in value attribute
    $resAdmin->assertSee(htmlspecialchars($longCaption, ENT_QUOTES), false);

    // 2. Public Detail View
    $resPublic = $this->get(route('projects.show', $this->project->id));
    $resPublic->assertStatus(200);

    // In show.blade.php, caption is rendered inside <p class="text-xs text-gray-700 dark:text-gray-300 leading-snug">
    $resPublic->assertSee(e($longCaption), false);
    $resPublic->assertSee('leading-snug');
});

test('navigation links and active route matching operate accurately across all pages', function () {
    // 1. Visit /projects (route 'projects')
    $resProjects = $this->get(route('projects'));
    $resProjects->assertStatus(200);
    $contentProjects = $resProjects->getContent();

    // Verify projects link is highlighted active
    expect($contentProjects)->toContain(route('projects'));
    // In navbar.blade.php: request()->routeIs('projects*') ? 'font-bold text-blue-600' : ...
    // Verify that the Projects link has font-bold text-blue-600
    preg_match('/<a href="[^"]*\/projects"[^>]*class="([^"]*)"[^>]*>Projects<\/a>/', $contentProjects, $matchesProjects);
    expect($matchesProjects)->not->toBeEmpty();
    expect($matchesProjects[1])->toContain('font-bold text-blue-600');

    // Verify Home link is NOT active
    preg_match('/<a href="[^"]*\/home"[^>]*class="([^"]*)"[^>]*>Home<\/a>/', $contentProjects, $matchesHome);
    if (! empty($matchesHome)) {
        expect($matchesHome[1])->not->toContain('font-bold text-blue-600');
    }

    // 2. Visit /projects/{id} (route 'projects.show')
    $resShow = $this->get(route('projects.show', $this->project->id));
    $resShow->assertStatus(200);
    $contentShow = $resShow->getContent();

    // In navbar.blade.php: request()->routeIs('projects*') matches projects.show!
    preg_match('/<a href="[^"]*\/projects"[^>]*class="([^"]*)"[^>]*>Projects<\/a>/', $contentShow, $matchesShowProjects);
    expect($matchesShowProjects)->not->toBeEmpty();
    expect($matchesShowProjects[1])->toContain('font-bold text-blue-600');

    // 3. Visit /dashboard when authenticated
    $resDash = $this->actingAs($this->user)->get(route('post.dashboard.index'));
    $resDash->assertStatus(200);
    $contentDash = $resDash->getContent();
    expect($contentDash)->toContain('Dashboard Admin');
    expect($contentDash)->toContain(route('post.projects.create'));
    expect($contentDash)->toContain(route('post.projects.index'));

    // 4. Verify all route helpers in navigation menus resolve without RouteNotFoundException
    $expectedRoutes = [
        'home',
        'about',
        'education',
        'projects',
        'posts.index',
        'dashboard',
        'login',
        'profile.edit',
        'logout',
    ];

    foreach ($expectedRoutes as $routeName) {
        expect(Route::has($routeName))->toBeTrue();
    }
});

test('layout containers and stat card components conform to clean visual specifications', function () {
    // 1. Root Layout Container width is max-w-5xl
    $layoutHtml = view('layouts.app')->render();
    expect($layoutHtml)->toContain('max-w-5xl');
    expect($layoutHtml)->not->toContain('max-w-2xl');

    // 2. Stat Card Component padding and typography
    $statHtml = view('components.stat-card', [
        'label' => 'Active Submissions',
        'value' => '42',
        'color' => 'text-emerald-600',
    ])->render();

    expect($statHtml)->toContain('p-4 sm:p-5');
    expect($statHtml)->toContain('text-2xl font-bold text-emerald-600 mt-2');
    expect($statHtml)->toContain('Active Submissions');
    expect($statHtml)->toContain('42');
});

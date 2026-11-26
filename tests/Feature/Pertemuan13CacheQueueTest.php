<?php

use App\Jobs\SendContactNotification;
use App\Models\Category;
use App\Models\Contact;
use App\Models\Education;
use App\Models\Experience;
use App\Models\Project;
use App\Models\Skill;
use App\Models\Technology;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Queue;

uses(RefreshDatabase::class);

beforeEach(function () {
    Cache::flush();

    $this->user = User::factory()->create([
        'name' => 'Admin Test',
        'email' => 'admin@example.com',
    ]);

    $this->category = Category::create([
        'name' => 'Web Development',
        'slug' => 'web-development',
    ]);

    $this->technology = Technology::create([
        'name' => 'Laravel 12',
        'slug' => 'laravel-12',
    ]);
});

test('home page caches featured projects and skills and reduces queries on second load', function () {
    Project::create([
        'judul' => 'Proyek Unggulan Satu',
        'slug' => 'proyek-unggulan-satu',
        'category_id' => $this->category->id,
        'kategori' => 'Web Development',
        'deskripsi' => 'Deskripsi proyek unggulan satu.',
        'status' => 'published',
        'is_featured' => true,
    ]);

    Skill::create(['name' => 'Laravel', 'level' => 92]);

    expect(Cache::has('portfolio.featured'))->toBeFalse();
    expect(Cache::has('portfolio.skills'))->toBeFalse();

    DB::enableQueryLog();
    $firstResponse = $this->get(route('home'));
    $firstQueries = count(DB::getQueryLog());
    DB::flushQueryLog();

    $firstResponse->assertStatus(200);
    $firstResponse->assertSee('Proyek Unggulan Satu');
    $firstResponse->assertSee('Laravel');
    $firstResponse->assertSee('PPWL Pertemuan 13');

    expect(Cache::has('portfolio.featured'))->toBeTrue();
    expect(Cache::has('portfolio.skills'))->toBeTrue();

    $secondResponse = $this->get(route('home'));
    $secondQueries = count(DB::getQueryLog());
    DB::disableQueryLog();

    $secondResponse->assertStatus(200);
    $secondResponse->assertSee('Proyek Unggulan Satu');
    expect($secondQueries)->toBeLessThan($firstQueries);
});

test('about and education pages cache experiences, skills, and educations', function () {
    Experience::create([
        'role' => 'Backend Intern',
        'organization' => 'UGM Lab',
        'type' => 'Magang',
        'start_date' => '2026-01-01',
        'is_current' => true,
        'sort_order' => 1,
    ]);

    Education::create([
        'institution' => 'Universitas Gadjah Mada',
        'degree' => 'D-IV TRPL',
        'start_year' => '2025',
        'is_current' => true,
        'sort_order' => 1,
    ]);

    DB::enableQueryLog();
    $aboutFirst = $this->get(route('about'));
    $aboutFirstQueries = count(DB::getQueryLog());
    DB::flushQueryLog();

    $aboutFirst->assertStatus(200);
    $aboutFirst->assertSee('Backend Intern');
    expect(Cache::has('portfolio.experiences'))->toBeTrue();

    $aboutSecond = $this->get(route('about'));
    $aboutSecondQueries = count(DB::getQueryLog());
    DB::flushQueryLog();

    $aboutSecond->assertStatus(200);
    expect($aboutSecondQueries)->toBeLessThan($aboutFirstQueries);

    $eduFirst = $this->get(route('education'));
    $eduFirstQueries = count(DB::getQueryLog());
    DB::flushQueryLog();

    $eduFirst->assertStatus(200);
    $eduFirst->assertSee('Universitas Gadjah Mada');
    expect(Cache::has('portfolio.educations'))->toBeTrue();

    $eduSecond = $this->get(route('education'));
    $eduSecondQueries = count(DB::getQueryLog());
    DB::disableQueryLog();

    $eduSecond->assertStatus(200);
    expect($eduSecondQueries)->toBeLessThan($eduFirstQueries);
});

test('projects page caches categories and technologies while keeping search dynamic', function () {
    Project::create([
        'judul' => 'Alpha Web App',
        'slug' => 'alpha-web-app',
        'category_id' => $this->category->id,
        'kategori' => 'Web Development',
        'deskripsi' => 'Aplikasi web alpha.',
        'status' => 'published',
    ]);

    Project::create([
        'judul' => 'Beta Mobile App',
        'slug' => 'beta-mobile-app',
        'category_id' => $this->category->id,
        'kategori' => 'Web Development',
        'deskripsi' => 'Aplikasi mobile beta.',
        'status' => 'published',
    ]);

    $resAll = $this->get(route('projects'));
    $resAll->assertStatus(200);
    $resAll->assertSee('Alpha Web App');
    $resAll->assertSee('Beta Mobile App');
    expect(Cache::has('portfolio.categories'))->toBeTrue();
    expect(Cache::has('portfolio.technologies'))->toBeTrue();

    $resSearch = $this->get(route('projects', ['search' => 'Beta']));
    $resSearch->assertStatus(200);
    $resSearch->assertSee('Beta Mobile App');
    $resSearch->assertDontSee('Alpha Web App');
});

test('admin project, category, skill, experience, and education mutations invalidate corresponding caches', function () {
    // Prime all caches
    $this->get(route('home'))->assertStatus(200);
    $this->get(route('about'))->assertStatus(200);
    $this->get(route('education'))->assertStatus(200);
    $this->get(route('projects'))->assertStatus(200);

    expect(Cache::has('portfolio.featured'))->toBeTrue();
    expect(Cache::has('portfolio.skills'))->toBeTrue();
    expect(Cache::has('portfolio.categories'))->toBeTrue();
    expect(Cache::has('portfolio.experiences'))->toBeTrue();
    expect(Cache::has('portfolio.educations'))->toBeTrue();

    // 1. Project store invalidates portfolio.featured
    $this->actingAs($this->user)->post(route('post.projects.store'), [
        'judul' => 'Proyek Cache Test',
        'category_id' => $this->category->id,
        'deskripsi' => 'Deskripsi proyek uji cache.',
        'status' => 'published',
        'is_featured' => true,
    ])->assertRedirect(route('post.projects.index'));
    expect(Cache::has('portfolio.featured'))->toBeFalse();

    // Re-prime featured
    $this->get(route('home'))->assertStatus(200);
    expect(Cache::has('portfolio.featured'))->toBeTrue();

    // 2. Category update invalidates BOTH portfolio.categories and portfolio.featured
    $this->actingAs($this->user)->put(route('post.categories.update', $this->category->id), [
        'name' => 'Web Engineering',
    ])->assertRedirect(route('post.categories.index'));
    expect(Cache::has('portfolio.categories'))->toBeFalse();
    expect(Cache::has('portfolio.featured'))->toBeFalse();

    // 3. Skill store invalidates portfolio.skills
    $this->actingAs($this->user)->post(route('post.skills.store'), [
        'name' => 'Docker',
        'level' => 85,
    ])->assertRedirect(route('post.skills.index'));
    expect(Cache::has('portfolio.skills'))->toBeFalse();

    // 4. Experience store invalidates portfolio.experiences
    $this->actingAs($this->user)->post(route('post.experiences.store'), [
        'role' => 'Software Engineer',
        'organization' => 'Tech Studio',
        'type' => 'Magang',
        'start_date' => '2026-02-01',
        'is_current' => true,
    ])->assertRedirect(route('post.experiences.index'));
    expect(Cache::has('portfolio.experiences'))->toBeFalse();

    // 5. Education store invalidates portfolio.educations
    $this->actingAs($this->user)->post(route('post.educations.store'), [
        'institution' => 'SMAN 1 Yogyakarta',
        'degree' => 'MIPA',
        'start_year' => '2022',
        'end_year' => '2025',
    ])->assertRedirect(route('post.educations.index'));
    expect(Cache::has('portfolio.educations'))->toBeFalse();
});

test('contact form dispatches SendContactNotification job to database queue', function () {
    config()->set('queue.default', 'database');

    $response = $this->post(route('contact.store'), [
        'name' => 'Budi Santoso',
        'email' => 'budi@example.com',
        'subject' => 'Kolaborasi Proyek',
        'message' => 'Halo, saya tertarik mengajak kolaborasi proyek Laravel.',
    ]);

    $response->assertRedirect();
    $response->assertSessionHas('success');

    expect(Contact::where('email', 'budi@example.com')->exists())->toBeTrue();
    expect(DB::table('jobs')->count())->toBe(1);
});

test('SendContactNotification logs info on success and logs error with contact_id on failure', function () {
    Queue::fake();

    $contact = Contact::create([
        'name' => 'Siti Aminah',
        'email' => 'siti@example.com',
        'subject' => 'Pertanyaan',
        'message' => 'Pesan uji coba notifikasi antrean.',
    ]);

    Log::shouldReceive('info')
        ->once()
        ->withArgs(function (string $message, array $context) use ($contact): bool {
            return str_contains($message, 'Siti Aminah')
                && ($context['contact_id'] ?? null) === $contact->id;
        });

    $job = new SendContactNotification($contact);
    $job->handle();

    Log::shouldReceive('error')
        ->once()
        ->withArgs(function (string $message, array $context) use ($contact): bool {
            return str_contains($message, (string) $contact->id)
                && ($context['contact_id'] ?? null) === $contact->id;
        });

    $failedJob = new SendContactNotification($contact, simulateFailure: true);
    expect(fn () => $failedJob->handle())->toThrow(RuntimeException::class);
    $failedJob->failed(new RuntimeException('Simulasi gagal pengiriman notifikasi kontak.'));
});

test('admin dashboard displays cache status and queue monitor widgets and handles clear, retry, and flush actions', function () {
    config()->set('queue.default', 'database');

    // Prime home cache and dispatch a pending job
    $this->get(route('home'))->assertStatus(200);
    $contact = Contact::create([
        'name' => 'Pengirim Antrean',
        'email' => 'queue@example.com',
        'subject' => 'Test Queue',
        'message' => 'Pesan untuk pengujian widget queue di dashboard.',
    ]);
    SendContactNotification::dispatch($contact);

    // Insert a simulated failed job record
    DB::table('failed_jobs')->insert([
        'uuid' => (string) str()->uuid(),
        'connection' => 'database',
        'queue' => 'default',
        'payload' => json_encode(['displayName' => SendContactNotification::class, 'data' => []]),
        'exception' => "RuntimeException: Simulasi gagal\nStack trace",
        'failed_at' => now(),
    ]);

    $resDash = $this->actingAs($this->user)->get(route('post.dashboard.index'));
    $resDash->assertStatus(200);
    $resDash->assertSee('Status Cache Portofolio');
    $resDash->assertSee('portfolio.featured');
    $resDash->assertSee('Cached (HIT)');
    $resDash->assertSee('Monitor Antrean (Queue)');
    $resDash->assertSee('Pending Jobs');
    $resDash->assertSee('Failed Jobs');

    // Clear single cache key
    $this->actingAs($this->user)->post(route('post.dashboard.cache.clear'), [
        'key' => 'portfolio.featured',
    ])->assertRedirect();
    expect(Cache::has('portfolio.featured'))->toBeFalse();
    expect(Cache::has('portfolio.skills'))->toBeTrue();

    // Clear all cache keys
    $this->actingAs($this->user)->post(route('post.dashboard.cache.clear'))
        ->assertRedirect();
    expect(Cache::has('portfolio.skills'))->toBeFalse();

    // Flush failed jobs
    $this->actingAs($this->user)->post(route('post.dashboard.queue.flush'))
        ->assertRedirect();
    expect(DB::table('failed_jobs')->count())->toBe(0);
});

test('new admin endpoints reject unauthenticated guests', function () {
    $this->get(route('post.categories.index'))->assertRedirect('/login');
    $this->get(route('post.experiences.index'))->assertRedirect('/login');
    $this->get(route('post.educations.index'))->assertRedirect('/login');
    $this->post(route('post.dashboard.cache.clear'))->assertRedirect('/login');
    $this->post(route('post.dashboard.queue.retry-all'))->assertRedirect('/login');
    $this->post(route('post.dashboard.queue.flush'))->assertRedirect('/login');
});

test('database cache store serializes and unserializes cached models cleanly across requests', function () {
    config()->set('cache.default', 'database');
    Cache::store('database')->flush();

    Project::create([
        'judul' => 'Proyek Database Cache',
        'slug' => 'proyek-database-cache',
        'category_id' => $this->category->id,
        'kategori' => 'Web Development',
        'deskripsi' => 'Menguji serialisasi Eloquent collection pada driver database cache.',
        'status' => 'published',
        'is_featured' => true,
    ]);

    Skill::create(['name' => 'Laravel Cache', 'level' => 90]);
    Experience::create([
        'role' => 'Developer',
        'organization' => 'UGM',
        'type' => 'Akademik',
        'start_date' => '2026-01-01',
        'is_current' => true,
    ]);
    Education::create([
        'institution' => 'UGM',
        'degree' => 'D-IV TRPL',
        'start_year' => '2025',
        'is_current' => true,
    ]);

    // First requests populate database cache (MISS)
    $this->get(route('home'))->assertStatus(200)->assertSee('Proyek Database Cache');
    $this->get(route('about'))->assertStatus(200)->assertSee('Developer');
    $this->get(route('education'))->assertStatus(200)->assertSee('D-IV TRPL');
    $this->get(route('projects'))->assertStatus(200)->assertSee('Proyek Database Cache');

    // Second requests unserialize from database cache (HIT)
    $this->get(route('home'))->assertStatus(200)->assertSee('Proyek Database Cache');
    $this->get(route('about'))->assertStatus(200)->assertSee('Developer');
    $this->get(route('education'))->assertStatus(200)->assertSee('D-IV TRPL');
    $this->get(route('projects'))->assertStatus(200)->assertSee('Proyek Database Cache');

    expect(Cache::store('database')->get('portfolio.featured')->count())->toBe(1);
});

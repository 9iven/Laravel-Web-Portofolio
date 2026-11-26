<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Contact;
use App\Models\Education;
use App\Models\Experience;
use App\Models\Project;
use App\Models\Skill;
use App\Models\Technology;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class DashboardController extends Controller
{
    /**
     * Daftar key cache portofolio yang dipantau di dashboard admin.
     *
     * @var array<string, string>
     */
    public const PORTFOLIO_CACHE_KEYS = [
        'portfolio.featured' => 'Proyek Unggulan (Home)',
        'portfolio.skills' => 'Daftar Keahlian (Home & About)',
        'portfolio.categories' => 'Kategori Proyek (Projects)',
        'portfolio.technologies' => 'Teknologi Proyek (Projects)',
        'portfolio.experiences' => 'Pengalaman (About)',
        'portfolio.educations' => 'Riwayat Pendidikan (Education)',
    ];

    public function index(): View
    {
        $cacheStatuses = collect(self::PORTFOLIO_CACHE_KEYS)->map(function (string $label, string $key): array {
            return [
                'key' => $key,
                'label' => $label,
                'active' => Cache::has($key),
            ];
        })->values();

        $pendingJobsCount = Schema::hasTable('jobs') ? DB::table('jobs')->count() : 0;
        $failedJobsCount = Schema::hasTable('failed_jobs') ? DB::table('failed_jobs')->count() : 0;
        $recentFailedJobs = Schema::hasTable('failed_jobs')
            ? DB::table('failed_jobs')->orderByDesc('failed_at')->take(5)->get(['id', 'uuid', 'queue', 'failed_at', 'exception'])
            : collect();

        return view('dashboard', [
            'totalProjects' => Project::count(),
            'totalPublished' => Project::where('status', 'published')->count(),
            'totalDraft' => Project::where('status', 'draft')->count(),
            'totalCategories' => Category::count(),
            'totalTechnologies' => Technology::count(),
            'totalSkills' => Skill::count(),
            'totalExperiences' => Schema::hasTable('experiences') ? Experience::count() : 0,
            'totalEducations' => Schema::hasTable('educations') ? Education::count() : 0,
            'categoriesWithCount' => Category::withCount('projects')->get(),
            'topSkills' => Skill::orderBy('level', 'desc')->take(5)->get(),
            'latestProjects' => Project::with('category')->latest()->take(5)->get(),
            'totalMessages' => Contact::count(),
            'unreadMessages' => Contact::where('is_read', false)->count(),
            'cacheStatuses' => $cacheStatuses,
            'pendingJobsCount' => $pendingJobsCount,
            'failedJobsCount' => $failedJobsCount,
            'recentFailedJobs' => $recentFailedJobs,
        ]);
    }

    /**
     * Menghapus satu key cache portofolio atau seluruh key cache yang dipantau.
     */
    public function clearCache(Request $request): RedirectResponse
    {
        $key = $request->input('key');

        if ($key !== null && $key !== '') {
            if (! array_key_exists($key, self::PORTFOLIO_CACHE_KEYS)) {
                return back()->with('error', 'Key cache tidak valid.');
            }

            Cache::forget($key);

            return back()->with('success', "Cache '{$key}' berhasil dibersihkan.");
        }

        foreach (array_keys(self::PORTFOLIO_CACHE_KEYS) as $cacheKey) {
            Cache::forget($cacheKey);
        }

        return back()->with('success', 'Seluruh cache portofolio berhasil dibersihkan.');
    }

    /**
     * Mengembalikan seluruh failed jobs ke antrean queue.
     */
    public function retryFailedJobs(): RedirectResponse
    {
        if (! Schema::hasTable('failed_jobs') || DB::table('failed_jobs')->count() === 0) {
            return back()->with('error', 'Tidak ada failed job untuk di-retry.');
        }

        Artisan::call('queue:retry', ['id' => ['all']]);

        return back()->with('success', 'Seluruh failed job dikembalikan ke antrean.');
    }

    /**
     * Menghapus seluruh catatan failed jobs dari database.
     */
    public function flushFailedJobs(): RedirectResponse
    {
        Artisan::call('queue:flush');

        return back()->with('success', 'Seluruh catatan failed job berhasil dihapus.');
    }
}

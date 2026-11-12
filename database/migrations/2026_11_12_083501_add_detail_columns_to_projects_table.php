<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('projects', function (Blueprint $table) {
            if (! Schema::hasColumn('projects', 'name')) {
                $table->string('name')->nullable();
            }
            if (! Schema::hasColumn('projects', 'slug')) {
                $table->string('slug')->nullable()->unique();
            }
            if (! Schema::hasColumn('projects', 'short_description')) {
                $table->string('short_description')->nullable();
            }
            if (! Schema::hasColumn('projects', 'description')) {
                $table->text('description')->nullable();
            }
            if (! Schema::hasColumn('projects', 'status')) {
                $table->string('status')->default('draft');
            }
            if (! Schema::hasColumn('projects', 'project_date')) {
                $table->date('project_date')->nullable();
            }
            if (! Schema::hasColumn('projects', 'is_featured')) {
                $table->boolean('is_featured')->default(false);
            }
            if (! Schema::hasColumn('projects', 'project_url')) {
                $table->string('project_url')->nullable();
            }
            if (! Schema::hasColumn('projects', 'repository_url')) {
                $table->string('repository_url')->nullable();
            }
        });

        // Sinkronisasi data lama agar tidak null
        $projects = DB::table('projects')->get();
        foreach ($projects as $project) {
            $nameVal = $project->name ?: ($project->judul ?? 'Project-'.$project->id);
            $slugBase = Str::slug($nameVal);
            $slugVal = $slugBase ?: 'project-'.$project->id;

            // Pastikan slug unik jika ada yang duplikat
            $count = DB::table('projects')->where('slug', $slugVal)->where('id', '!=', $project->id)->count();
            if ($count > 0) {
                $slugVal = $slugVal.'-'.$project->id;
            }

            $shortDesc = $project->short_description ?: (isset($project->deskripsi) ? Str::limit(strip_tags($project->deskripsi), 120) : null);
            $desc = $project->description ?: ($project->deskripsi ?? null);
            $pUrl = $project->project_url ?: ($project->tautan ?? null);
            $pDate = $project->project_date ?: ($project->created_at ? substr($project->created_at, 0, 10) : now()->toDateString());

            DB::table('projects')->where('id', $project->id)->update([
                'name' => $nameVal,
                'slug' => $slugVal,
                'short_description' => $shortDesc,
                'description' => $desc,
                'project_url' => $pUrl,
                'project_date' => $pDate,
            ]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('projects', function (Blueprint $table) {
            $table->dropUnique(['slug']);
            $table->dropColumn([
                'name',
                'slug',
                'short_description',
                'description',
                'project_date',
                'is_featured',
                'project_url',
                'repository_url',
            ]);
        });
    }
};

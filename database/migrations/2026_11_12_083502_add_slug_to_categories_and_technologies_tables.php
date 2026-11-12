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
        Schema::table('categories', function (Blueprint $table) {
            $table->string('slug')->nullable()->unique()->after('name');
        });

        Schema::table('technologies', function (Blueprint $table) {
            $table->string('slug')->nullable()->unique()->after('name');
        });

        // Backfill data categories
        $categories = DB::table('categories')->get();
        foreach ($categories as $cat) {
            $slug = Str::slug($cat->name) ?: 'cat-'.$cat->id;
            DB::table('categories')->where('id', $cat->id)->update(['slug' => $slug]);
        }

        // Backfill data technologies
        $technologies = DB::table('technologies')->get();
        foreach ($technologies as $tech) {
            $slug = Str::slug($tech->name) ?: 'tech-'.$tech->id;
            DB::table('technologies')->where('id', $tech->id)->update(['slug' => $slug]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('categories', function (Blueprint $table) {
            $table->dropUnique(['slug']);
            $table->dropColumn('slug');
        });

        Schema::table('technologies', function (Blueprint $table) {
            $table->dropUnique(['slug']);
            $table->dropColumn('slug');
        });
    }
};

<?php

namespace App\Observers;

use App\Models\Post;
use Illuminate\Support\Facades\Log;

class PostObserver
{
    /**
     * Handle the Post "creating" event.
     */
    public function creating(Post $post): void
    {
        Log::info('Observer creating dipanggil untuk post: '.$post->title);
    }

    /**
     * Handle the Post "created" event.
     */
    public function created(Post $post): void
    {
        Log::info('Observer created dipanggil, id post: '.$post->id);
    }

    /**
     * Handle the Post "updating" event.
     */
    public function updating(Post $post): void
    {
        Log::info('Observer updating dipanggil, judul lama: '.$post->getOriginal('title'));
    }

    /**
     * Handle the Post "updated" event.
     */
    public function updated(Post $post): void
    {
        Log::info('Observer updated dipanggil, judul baru: '.$post->title);
    }

    /**
     * Handle the Post "deleted" event.
     */
    public function deleted(Post $post): void
    {
        Log::info('Observer deleted dipanggil, post masuk sampah: '.$post->title);
    }

    /**
     * Handle the Post "restored" event.
     */
    public function restored(Post $post): void
    {
        Log::info('Observer restored dipanggil, post dikembalikan: '.$post->title);
    }

    /**
     * Handle the Post "force deleted" event.
     */
    public function forceDeleted(Post $post): void
    {
        Log::info('Observer forceDeleted dipanggil, post dihapus permanen: '.$post->title);
    }
}

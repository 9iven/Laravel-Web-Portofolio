<?php

namespace App\Jobs;

use App\Models\Contact;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Log;
use RuntimeException;
use Throwable;

class SendContactNotification implements ShouldQueue
{
    use Queueable;

    public int $tries = 2;

    /**
     * Create a new job instance.
     */
    public function __construct(
        public Contact $contact,
        public bool $simulateFailure = false,
    ) {}

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        if ($this->simulateFailure) {
            throw new RuntimeException('Simulasi gagal pengiriman notifikasi kontak.');
        }

        if (! app()->runningUnitTests()) {
            sleep(3);
        }

        Log::info("Pesan baru dari {$this->contact->name}", [
            'contact_id' => $this->contact->id,
            'email' => $this->contact->email,
            'subject' => $this->contact->subject,
        ]);
    }

    /**
     * Handle a job failure.
     */
    public function failed(Throwable $exception): void
    {
        Log::error("Job SendContactNotification gagal untuk contact_id={$this->contact->id}: {$exception->getMessage()}", [
            'contact_id' => $this->contact->id,
            'email' => $this->contact->email,
        ]);
    }
}

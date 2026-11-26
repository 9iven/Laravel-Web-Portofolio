<?php

namespace App\Http\Controllers;

use App\Jobs\SendContactNotification;
use App\Models\Contact;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ContactController extends Controller
{
    /**
     * Menampilkan formulir kontak publik.
     */
    public function create(): View
    {
        return view('contact');
    }

    /**
     * Menyimpan pesan yang dikirim oleh pengunjung ke database lalu mengirim notifikasi via queue.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|max:100',
            'email' => 'required|email|max:100',
            'subject' => 'nullable|max:200',
            'message' => 'required|min:10',
        ]);

        $contact = Contact::create($validated);

        SendContactNotification::dispatch($contact);

        return back()->with('success', 'Pesan berhasil dikirim. Terima kasih!');
    }
}

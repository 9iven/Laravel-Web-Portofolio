<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class ContactAdminController extends Controller
{
    /**
     * Menampilkan daftar pesan masuk dengan urutan terbaru dan pagination 10 data.
     */
    public function index(): View
    {
        $contacts = Contact::latest()->paginate(10);

        return view('posts.contacts.index', compact('contacts'));
    }

    /**
     * Menampilkan detail pesan dan otomatis menandai pesan sudah dibaca (is_read = true).
     */
    public function show(Contact $contact): View
    {
        if (! $contact->is_read) {
            $contact->update(['is_read' => true]);
        }

        return view('posts.contacts.show', compact('contact'));
    }

    /**
     * Menghapus pesan kontak jika diperlukan.
     */
    public function destroy(Contact $contact): RedirectResponse
    {
        $contact->delete();

        return redirect()->route('post.contacts.index')->with('success', 'Pesan berhasil dihapus.');
    }
}

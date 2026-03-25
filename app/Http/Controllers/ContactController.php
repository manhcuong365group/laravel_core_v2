<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use App\Models\SeoPage;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ContactController extends Controller
{
    /**
     * Show the contact page.
     */
    public function index(): View
    {
        $seo = SeoPage::getForPage('contact');

        return view('theme::pages.contact', [
            'seo' => $seo,
        ]);
    }

    /**
     * Handle contact form submission.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:20',
            'subject' => 'nullable|string|max:255',
            'message' => 'required|string|max:2000',
        ]);

        Contact::create([
            ...$validated,
            'status' => 'new',
            'ip_address' => $request->ip(),
        ]);

        return back()->with('success', 'Cam on ban da lien he! Chung toi se phan hoi som nhat co the.');
    }
}

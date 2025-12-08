<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use Illuminate\Http\Request;

class ContactController extends Controller
{
    public function store(Request $request)
    {
        // Validasi input
        $request->validate([
            'name' => 'required|string|max:100',
            'email' => 'required|email|max:100',
            'whatsapp' => 'required|string|max:20',
            'message' => 'required|string|max:1000',
        ]);

        // Simpan ke database
        Contact::create([
            'name' => $request->name,
            'email' => $request->email,
            'whatsapp' => $request->whatsapp,
            'message' => $request->message,
        ]);

        // Redirect dengan pesan sukses
        return redirect()->back()->with('success', 'Pesan Anda berhasil dikirim!');
    }
}


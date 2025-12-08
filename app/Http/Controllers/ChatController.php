<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Chat;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;

class ChatController extends Controller
{
    /**
     * Menampilkan chat untuk pengguna biasa
     */
    public function index()
    {
        $chats = Chat::where('sender_id', Auth::id())
                     ->orWhere('receiver_id', Auth::id())
                     ->orderBy('created_at', 'desc')
                     ->get();
        return view('frontend.chat.index', compact('chats'));
    }

    /**
     * Menyimpan pesan chat oleh pengguna biasa
     */
    public function store(Request $request)
    {
        $request->validate([
            'message' => 'required|string',
        ]);

        // Cari admin pertama (atau sesuaikan logika Anda)
        $admin = User::where('role', 'admin')->first();
        if (!$admin) {
            return redirect()->back()->with('error', 'Admin tidak ditemukan');
        }

        $chat = new Chat();
        $chat->sender_id = Auth::id();
        $chat->receiver_id = $admin->id;
        $chat->message = $request->message;
        $chat->save();

        return redirect()->route('chat.index');
    }

    /**
     * ADMIN: Menampilkan daftar user yang pernah chat dengan admin
     */
    public function adminChats()
    {
        // Ambil user yang mengirim pesan ke admin
        $users = User::whereHas('chatsSent', function ($query) {
            $query->where('receiver_id', Auth::id()); // Admin sebagai penerima
        })
        ->orWhereHas('chatsReceived', function ($query) {
            $query->where('sender_id', Auth::id()); // Admin sebagai pengirim
        })
        ->distinct()
        ->get();

        return view('admin.chat.user', compact('users'));
    }

    /**
     * ADMIN: Menampilkan chat dengan user tertentu
     */
public function adminChatIndex($userId)
{
    $user = User::findOrFail($userId);

    // Ambil semua chat antara admin dan user ini
    $chats = Chat::where(function ($query) use ($userId) {
        $query->where('sender_id', Auth::id())->where('receiver_id', $userId);
    })
    ->orWhere(function ($query) use ($userId) {
        $query->where('sender_id', $userId)->where('receiver_id', Auth::id());
    })
    ->orderBy('created_at', 'asc')
    ->get();

    return view('admin.chat.index', compact('chats', 'user'));
}
    /**
     * ADMIN: Menyimpan pesan chat dari admin
     */
    public function adminStore(Request $request)
    {
        $request->validate([
            'message' => 'required|string',
            'user_id' => 'required|exists:users,id'
        ]);

        $user = User::findOrFail($request->user_id);

        $chat = new Chat();
        $chat->sender_id = Auth::id(); // Admin yang mengirim
        $chat->receiver_id = $user->id; // User yang menerima
        $chat->message = $request->message;
        $chat->save();

        return redirect()->route('admin.chat.index', ['userId' => $user->id]);
    }
}
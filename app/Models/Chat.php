<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Chat extends Model
{
    use HasFactory;

    protected $fillable = ['sender_id', 'receiver_id', 'message'];

    /**
     * Relasi ke model User (Pengirim pesan)
     */public function chatsSent()
{
    return $this->hasMany(Chat::class, 'sender_id');
}

public function chatsReceived()
{
    return $this->hasMany(Chat::class, 'receiver_id');
}
    public function sender()
    {
        return $this->belongsTo(User::class, 'sender_id');
    }

    /**
     * Relasi ke model User (Penerima pesan)
     */
    public function receiver()
    {
        return $this->belongsTo(User::class, 'receiver_id');
    }

}

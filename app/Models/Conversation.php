<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Conversation extends Model
{
    protected $fillable = ['masyarakat_id', 'pemerintah_id'];

    public function masyarakat()
    {
        return $this->belongsTo(User::class, 'masyarakat_id');
    }

    public function pemerintah()
    {
        return $this->belongsTo(User::class, 'pemerintah_id');
    }

    public function messages()
    {
        return $this->hasMany(Message::class);
    }

    public function lastMessage()
    {
        return $this->hasOne(Message::class)->latestOfMany();
    }

    public function unreadMessagesForPemerintah()
    {
        return $this->messages()
                    ->where('sender_id', $this->masyarakat_id)
                    ->where('is_read', false)
                    ->count();
    }

    public function unreadMessagesForMasyarakat()
    {
        return $this->messages()
                    ->where('sender_id', $this->pemerintah_id)
                    ->where('is_read', false)
                    ->count();
    }

    public function otherUser()
    {
        return auth()->id() === $this->masyarakat_id
            ? $this->pemerintah
            : $this->masyarakat;
    }
}

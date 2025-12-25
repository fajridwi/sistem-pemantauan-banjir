<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

// app/Models/OtpVerification.php
class OtpVerification extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id','code','expired_at'
    ];

    protected $dates = ['expired_at'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}


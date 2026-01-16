<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Notification extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'from_user_id',
        'message',
        'type',
        'read_at',
    ];

    protected $casts = [
        'read_at' => 'datetime',
    ];

    // Recipient
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Sender
    public function sender()
    {
        return $this->belongsTo(User::class, 'from_user_id');
    }
}

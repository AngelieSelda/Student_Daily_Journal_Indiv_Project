<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Journal extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'content',
        'status',
        'user_id',
    ];

    // Connects journal to student (User)
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
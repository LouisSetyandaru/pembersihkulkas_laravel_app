<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReciptMaker extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'message',
        'is_ai',
    ];

    protected $casts = [
        'is_ai' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
}

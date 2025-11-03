<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Candidate extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'assessment',
        'tier',
    ];

    protected $casts = [
        'assessment' => 'array',
        'tier' => 'integer',
    ];
}

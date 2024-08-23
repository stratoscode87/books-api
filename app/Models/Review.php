<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    use HasFactory;

    protected $fillable = [
        'work_id',
        'score',
        'review',
        'authors',
        'title',
        'description',
        'cover_img',
        'status',
    ];
}

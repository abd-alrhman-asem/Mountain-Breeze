<?php

namespace App\Models;

use App\Traits\UploadImage;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory, UploadImage;

    protected $fillable = [
        'title',
        'summary',
        'description',
        'lang',
    ];

}

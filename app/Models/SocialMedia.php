<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\UploadImage;

class SocialMedia extends Model
{
    use HasFactory,UploadImage;

    protected $fillable = [
        'name',
        'link',
    ];
}

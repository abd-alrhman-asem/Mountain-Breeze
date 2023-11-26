<?php

namespace App\Models;

use App\Traits\UploadImage;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\UploadVideo;

class Post extends Model
{
    use HasFactory, UploadImage , UploadVideo;

    protected $fillable = [
        'title',
        'summary',
        'description',
        'lang',
        'category_id'
    ];

    public function category(){
        return $this->belongsTo(Category::class);
    }

}

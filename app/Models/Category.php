<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\UploadImage;

class Category extends Model
{
    use HasFactory,UploadImage;

    protected $fillable = [
        'category_id',
        'name',
        'summary',
        'language_id',
    ];

    public function categories(){
        return $this->hasMany(Category::class,'category_id','id');
    }

    public function subCategories(){
        return $this->hasMany(Category::class)->with('categories');
    }

    public function posts(){
        return $this->hasMany(Post::class);
    }

    public function videos(){
        return $this->hasMany(Video::class);
    }

    public function images(){
        return $this->hasMany(Image::class);
    }
    public function langauges()
    {
        return $this->belongsTo(Language::class,'language_id','id');
    }
}

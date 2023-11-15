<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id',
        'name',
        'summary',
        'lang',
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
}

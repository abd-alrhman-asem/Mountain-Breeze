<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'summary',
        'description',
        'lang',
    ];

    public function images(){
        return $this->morphMany(Image::class,'imagable');
    }
}

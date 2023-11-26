<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Video extends Model
{
    use HasFactory;

    protected $fillable = [
        'video',
        'category_id',
    ];

    public function videoable(){
        return $this->morphTo();
    }
    public function category(){
        return $this->belongsTo(Category::class);
    }
}

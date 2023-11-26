<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Image extends Model
{
    use HasFactory;

    protected $fillable = [
        'url',
        'category_id',
    ];

    public function imagable(){
        return $this->morphTo();
    }
    public function category(){
        return $this->belongsTo(Category::class);
    }
}

<?php

namespace App\Models;

use App\Models\Article;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Tag extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'name',
        'language_id',
    ];

        /**
     * Get all of the comments for the User
     *
     */
    public function articles()
    {
        return $this->belongsToMany(Article::class);
    }
    public function langauges()
    {
        return $this->belongsTo(Language::class,'language_id','id');
    }
}

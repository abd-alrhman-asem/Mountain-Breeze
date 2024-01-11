<?php

namespace App\Models;

use App\Models\Article;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Tag extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'name',
        'language_id',
    ];

        /**
     * Get all the comments for the User
     *
     */
    public function articles(): BelongsToMany
    {
        return $this->belongsToMany(Article::class);
    }
    public function langauges(): BelongsTo
    {
        return $this->belongsTo(Language::class,'language_id','id');
    }
}

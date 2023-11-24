<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Traits\UploadImage;

class Article extends Model
{
    use HasFactory ;
    use  SoftDeletes,UploadImage;

    protected $dates = ['deleted_at'];

    protected $fillable = [
        'title',
        'summary',
        'description',
        'created_at',
        'deleted_at',
        'lang',
    ];

    /**
     * Get all of the comments for the User
     *
     */
    public function tags()
    {
        return $this->belongsToMany(Tag::class);
    }

}

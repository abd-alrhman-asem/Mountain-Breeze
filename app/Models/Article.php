<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Traits\UploadImage;
use App\Traits\UploadVideo;

class Article extends Model
{
    use HasFactory ;
    use  SoftDeletes,UploadImage,UploadVideo;

    protected $dates = ['deleted_at'];

    protected $fillable = [
        'title',
        'summary',
        'description',
        'created_at',
        'deleted_at',
        'language_id',
    ];

    /**
     * Get all the comments for the User
     *
     */
    public function tags()
    {
        return $this->belongsToMany(Tag::class);
    }
    public function langauges()
    {
        return $this->belongsTo(Language::class,'language_id','id');
    }

}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\UploadImage;

class Service extends Model
{
    use HasFactory,UploadImage;

    protected $fillable = [
        'name',
        'language_id',
    ];

    /**
     * Get all of the comments for the User
     *
     */
    public function rooms()
    {
        return $this->belongsToMany(Room::class);
    }
    public function langauges()
    {
        return $this->belongsTo(Language::class,'language_id','id');
    }
}

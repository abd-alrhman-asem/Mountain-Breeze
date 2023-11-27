<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Traits\UploadImage;

class Room extends Model
{
    use HasFactory;
    use  SoftDeletes,UploadImage;
    protected $fillable = [
        'name',
        'description',
        'summary',
        'price_per_night',
        'guest_number',
        'location',
        'room_type_id',
        'language_id',
    ];

    public function types(){
        return $this->belongsTo(RoomType::class ,'room_type_id','id' );
    }

    /**
     * Get all of the comments for the User
     *
     */
    public function services()
    {
        return $this->belongsToMany(Service::class);
    }
    public function langauges()
    {
        return $this->belongsTo(Language::class,'language_id','id');
    }
}

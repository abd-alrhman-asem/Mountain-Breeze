<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RoomType extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'language_id',
    ];
    public function langauges()
    {
        return $this->belongsTo(Language::class,'language_id','id');
    }
}

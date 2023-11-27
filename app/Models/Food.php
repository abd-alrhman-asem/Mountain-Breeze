<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\UploadImage;

class Food extends Model
{
    use HasFactory,UploadImage;

    protected $fillable = [
        'title',
        'description',
        'language_id',
        'food_category_id',
    ];

    public function category(){
        return $this->belongsTo(FoodCategory::class,'food_category_id','id');
    }
    public function langauges()
    {
        return $this->belongsTo(Language::class,'language_id','id');
    }

}

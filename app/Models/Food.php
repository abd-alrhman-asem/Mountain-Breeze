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
        'lang',
        'food_category_id',
    ];

    public function category(){
        return $this->belongsTo(FoodCategory::class,'food_category_id','id');
    }

}

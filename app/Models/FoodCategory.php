<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FoodCategory extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'summary',
        'language_id',
    ];

    public function foods(){
        return $this->hasMany(Food::class);
    }
    public function langauges()
    {
        return $this->belongsTo(Language::class,'language_id','id');
    }
}

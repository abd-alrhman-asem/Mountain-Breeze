<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Language extends Model
{
    use HasFactory;
    protected $fillable =[
        'id',
        'name',
    ];
    public function articles(){
        return $this->hasMany(Article::class);
    }
    public function category(){
        return $this->hasMany(Category::class);
    }
    public function food(){
        return $this->hasMany(Food::class);
    }
    public function foodCategory(){
        return $this->hasMany(FoodCategory::class);
    }
    public function general(){
        return $this->hasMany(General::class);
    }
    public function post(){
        return $this->hasMany(Post::class);
    }
    public function room(){
        return $this->hasMany(Room::class);
    }
    public function roomType(){
        return $this->hasMany(RoomType::class);
    }
    public function service(){
        return $this->hasMany(Service::class);
    }
    public function tag(){
        return $this->hasMany(Tag::class);
    }
}

<?php

namespace App\Traits;

use App\Models\Image;
use Illuminate\Support\Facades\File;


trait UploadImage
{
    public function images(){
        return $this->morphMany(Image::class,'imagable');
    }

    public function StoreImage($photo ,$folder){
        $file_extention =$photo->getClientOriginalExtension();
        $file_name = microtime().'.'.$file_extention;
        $path=$folder;
        $photo->storeAs($path,$file_name);
        return $file_name;
    }

    public function DeleteImage($path,$image){
          File::delete($path.$image->url);
          Image::find($image->id)->delete();
    }

    public function ValidateImage($image){
        return $image->validate(['url'=>'required|image|mimes:jpeg,png,jpg,gif']);
    }

}

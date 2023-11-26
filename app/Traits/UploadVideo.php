<?php

namespace App\Traits;

use App\Models\Video;
use Illuminate\Support\Facades\File;


trait UploadVideo
{
    public function videos(){
        return $this->morphMany(Video::class,'videoable');
    }

    public function StoreVideo($video ,$folder){
        $file_extention =$video->getClientOriginalExtension();
        $file_name = microtime().'.'.$file_extention;
        $path=$folder;
        $video->storeAs($path,$file_name);
        return $file_name;
    }

    public function DeleteVideo($path,$video){
          File::delete($path.$video->video);
          Video::find($video->id)->delete();
    }
}

<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\StoreVideoRequest;
use App\Models\Video;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\VideoResource;
use App\Traits\UploadVideo;
use App\Traits\APIResponseTrait;

class VideoController extends Controller
{
    use UploadVideo , APIResponseTrait;
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try{
            $videos = Video::paginate(7);
            if ($request->has('category_id')) {
                $videos = Video::where('category_id', '=', $request->category_id)->paginate(7);
            }
            return VideoResource::collection($videos) ;
        } catch (\Throwable $th) {
            return $this->FailResponse('there are no videos');
        }

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreVideoRequest $request)
    {
        try{
            $file_name  = $this->StoreVideo($request->video, 'public/Videos/Dashboard');
            $video = Video::create([
                'video'      =>$file_name,
                'category_id'=>$request->category_id,
            ]);
            return new VideoResource($video);

        } catch (\Throwable $th) {
            return $this->FailResponse('create  not done');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}

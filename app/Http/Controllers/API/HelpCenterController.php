<?php

namespace App\Http\Controllers\API;

use App\Models\HelpCenter;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\HelpCenterResource;
use App\Http\Requests\StoreHelpCenterRequest;
use App\Traits\APIResponseTrait;

class HelpCenterController extends Controller
{
    use APIResponseTrait;
    public function __construct()
    {
        $this->middleware('auth:api',['except' => ['show','store']]);
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $questions = HelpCenter::all();
            $args['data'] = HelpCenterResource::collection($questions);
            return $this->successResponse($args , 200 );
        } catch (\Throwable $th) {
            return $this->FailResponse($th->getMessage());
        }
    }
    /**
     * Display a listing of the deleted resource.
     */
    public function deleted_questions()
    {
        try {
            $questions = HelpCenter::onlyTrashed()->get();
            $args['data'] = HelpCenterResource::collection($questions);
            return $this->successResponse($args , 200 );
        } catch (\Throwable $th) {
            return $this->FailResponse($th->getMessage());
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreHelpCenterRequest $request)
    {
        try {
            $validated = $request->validated();
            $question =  new HelpCenter();

            $question->full_name = $request->full_name;
            $question->phone     = $request->phone;
            $question->email     = $request->email;
            $question->subject   = $request->subject;
            $question->message   = $request->message;

            $question->save();
            $args['message'] = ' question stored successfully ';
            $args['data'] =new HelpCenterResource($question);
            return $this->successResponse($args , 200 );
            return $this->successResponse();
        } catch (\Throwable $th) {
            return $this->FailResponse($th->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            $question = HelpCenter::findOrFail($id);
            $args['data'] = new HelpCenterResource($question);
            return $this->successResponse($args , 200 );
        } catch (\Throwable $th) {
            return $this->FailResponse($th->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.st
     */
    public function destroy(Request $request)
    {
        try {
            $ids = explode(",", $request->deleted_ids);
            HelpCenter::destroy($ids);
            $args['message'] = 'question deleted successfully ';
            return $this->successResponse($args , 200);
        } catch (\Throwable $th) {
            return $this->FailResponse($th->getMessage());
        }
    }
}

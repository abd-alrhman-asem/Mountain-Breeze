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
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $questions = HelpCenter::all();
            return $this->successResponse(HelpCenterResource::collection($questions));
        } catch (\Throwable $th) {
            return $this->FailResponse('there are no questions');
        }
    }
    /**
     * Display a listing of the deleted resource.
     */
    public function deleted_questions()
    {
        try {
            $questions = HelpCenter::onlyTrashed()->get();
            return $this->successResponse(HelpCenterResource::collection($questions));
        } catch (\Throwable $th) {
            return $this->FailResponse('there are no questions');
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
            return $this->successResponse(new HelpCenterResource($question));
        } catch (\Throwable $th) {
            return $this->FailResponse('create not done');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            $question = HelpCenter::findOrFail($id);
            return $this->successResponse(new HelpCenterResource($question));
        } catch (\Throwable $th) {
            return $this->FailResponse('there is no question');
        }
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
    public function destroy(HelpCenter $helpCenter)
    {
        try {
            $helpCenter->delete();
            return $this->successResponse();
        } catch (\Throwable $th) {
            return $this->FailResponse('there is no question to delete');
        }
    }
}

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
            return $this->successResponse(HelpCenterResource::collection($questions));
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
            return $this->successResponse(new HelpCenterResource($question));
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
            return $this->successResponse(new HelpCenterResource($question));
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
            return $this->successResponse();
        } catch (\Throwable $th) {
            return $this->FailResponse($th->getMessage());
        }
    }
}

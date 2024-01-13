<?php

namespace App\Http\Controllers\API;

use App\Models\HelpCenter;
use Illuminate\Http\JsonResponse;
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
        $this->middleware('auth:api', ['except' => ['show', 'store']]);
    }

    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        try {
            $questions = HelpCenter::all();
            if (!$questions)
                return $this->errorResponse('there are no questions in help center');
            return $this->successResponse(HelpCenterResource::collection($questions));
        } catch (\Throwable $th) {
            return $this->generalFailureResponse($th->getMessage());
        }
    }

    /**
     * Display a listing of the deleted resource.
     */
    public function deleted_questions(): JsonResponse
    {
        try {

            $questions = HelpCenter::onlyTrashed()->get();
            if ($questions->isEmpty())
                return $this->errorResponse('there are no deleted questions. ');
            return $this->successResponse(HelpCenterResource::collection($questions));
        } catch (\Throwable $th) {
            return $this->generalFailureResponse($th->getMessage());
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreHelpCenterRequest $request)
    {
        try {
            $question = new HelpCenter();
            $question->full_name = $request->full_name;
            $question->phone = $request->phone;
            $question->email = $request->email;
            $question->subject = $request->subject;
            $question->message = $request->message;
            $question->save();
            return $this->successOperationResponse(' question stored successfully ');
        } catch (\Throwable $th) {
            return $this->generalFailureResponse($th->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id): JsonResponse
    {
        try {
            $question = HelpCenter::find($id);
            if (!$question)
                return $this->errorResponse('there are no question for this id ');
            return $this->successResponse(new HelpCenterResource($question));
        } catch (\Throwable $th) {
            return $this->generalFailureResponse($th->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.st
     * @param Request $request
     * @return JsonResponse
     */
    public function destroy(Request $request): JsonResponse
    {
        try {
            if (!$request->deleted_ids)
                return $this->errorResponse('please enter the ides to delete them ');
             $ids = $request->deleted_ids;
            if (HelpCenter::destroy($ids))
                return $this->successOperationResponse("questions deleted successfully ");
            else
                return $this->errorResponse('delete not complete');

        } catch (\Throwable $th) {
            return $this->generalFailureResponse($th->getMessage());
        }
    }
}

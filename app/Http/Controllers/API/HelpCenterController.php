<?php

namespace App\Http\Controllers\API;

use App\Models\HelpCenter;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\HelpCenterResource;
use App\Http\Requests\StoreHelpCenterRequest;

class HelpCenterController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $questions = HelpCenter::all();
        return response()->json(HelpCenterResource::collection($questions), 200);

    }
     /**
     * Display a listing of the deleted resource.
     */
    public function deleted_questions()
    {
        $questions = HelpCenter::onlyTrashed()->get();
        return response()->json(HelpCenterResource::collection($questions), 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreHelpCenterRequest $request)
    {
        $validated = $request->validated();
        $question =  new HelpCenter();

        $question->full_name = $request->fullname;
        $question->phone     = $request->phone;
        $question->email     = $request->email;
        $question->subject   = $request->subject;
        $question->message   = $request->message;

        $question->save();

        return response()->json(new HelpCenterResource($question), 200);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $question = HelpCenter::findOrFail($id);

        return response()->json(new HelpCenterResource($question), 200);
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
        $helpCenter->delete();
        return response()->json('Deleted Done', 200);
    }
}

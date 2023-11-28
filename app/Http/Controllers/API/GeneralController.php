<?php

namespace App\Http\Controllers\API;

use App\Models\General;
use App\Models\Language;
use Illuminate\Http\Request;
use App\Traits\APIResponseTrait;
use App\Http\Controllers\Controller;
use App\Http\Resources\GeneralResource;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\StoreGeneralRequest;
use App\Http\Requests\UpdateGeneralRequest;

class GeneralController extends Controller
{
    use APIResponseTrait;
    public function __construct()
    {
        $this->middleware('auth:api',['except' => ['index','show']]);
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try {
            $information = General::all();
            if ($request->header('language')) {
                $language_header = $request->header('language');
                $language = Language::where('name', '=', $language_header)->first();

                $information = General::whereHas('langauges', function ($query) use ($language) {
                    $query->where('language_id', '=', $language->id);
                })->get();
            }
            $args['data'] = GeneralResource::collection($information);
            return $this->successResponse($args , 200 );
        } catch (\Throwable $th) {
            return $this->FailResponse($th->getMessage());
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreGeneralRequest $request)
    {
        try {
            $validated = $request->validated();

            $information =   General::create([
                'name' => $request->name,
                'value' => $request->value,
                'language_id' => $request->language_id,
            ]);
            if ($request->hasFile('icon')) {
                $icon = $request->file('icon');
                $filename = time() . '.' .  $icon->getClientOriginalExtension();
                $icon->storeAs('public/images', $filename);
                $information->icon = $filename;
                $information->save();
            }
            $args['message'] = 'general details  stored successfully ';
            $args['data'] =new GeneralResource($information);
            return $this->successResponse($args , 200 );
        } catch (\Throwable $th) {
            return $this->FailResponse($th->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id, Request $request)
    {
        try {
            $information = General::findOrFail($id);
            if ($request->header('language')) {
                $language_header = $request->header('language');
                $language = Language::where('name', '=', $language_header)->first();
                if ($language->id == $information->language_id) {
                    $information = General::whereHas('langauges', function ($query) use ($language) {
                        $query->where('language_id', '=', $language->id);
                    })->first();
                } else {
                    return $this->FailResponse('go out');
                }
            }
            $args['data'] =new GeneralResource($information);
            return $this->successResponse($args , 200 );
        } catch (\Throwable $th) {
            return $this->FailResponse($th->getMessage());
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateGeneralRequest $request, string $id)
    {
        try {
            $general = General::findOrFail($id);

            if ($request->has('icon')) {
                Storage::delete('public/images/' . $general->icon);
                $icon = $request->file('icon');
                $filename = time() . '.' .  $icon->getClientOriginalExtension();
                $icon->storeAs('public/images', $filename);
                $general->icon = $filename;
            }
            $general->update([
                'name' => $request->name,
                'value' => $request->value,
                'language_id' => $request->language_id,
                'icon' => $filename,
            ]);
            $args['message'] = 'general details  updated successfully ';
            $args['data'] =new GeneralResource($general);
            return $this->successResponse($args , 200 );
        } catch (\Throwable $th) {
            return $this->FailResponse($th->getMessage());
        }
    }
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(General $general)
    {
        try {
            Storage::delete('public/images/' . $general->icon);
            $general->delete();

            $args['message'] = 'general details  deleted successfully ';
            return $this->successResponse($args , 200);
        } catch (\Throwable $th) {
            return $this->FailResponse($th->getMessage());
        }
    }
}

<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreRoomRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name'           =>'required|string',
            'description'    =>'required|string',
            'summary'        =>'required|string',
            'price_per_night'=>'required|integer',
            'guest_number'   =>'required|integer',
            'location'       =>'required|string',
            'room_type_id'   =>'required|integer|exists:room_types,id',
        ];
    }
}

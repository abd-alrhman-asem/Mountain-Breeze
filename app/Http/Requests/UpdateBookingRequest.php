<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateBookingRequest extends FormRequest
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
            'name' =>'required|string',
            'email'=>'required|email',
            'phone'=>'required|string',
            'check_in'=> 'required|date',
            'check_out'=> 'required|date',
            'description'=>'string',
            'guest_number'=> 'required|integer',
            'room_type_id'=>'required|integer|exists:room_types,id',
        ];
    }
}
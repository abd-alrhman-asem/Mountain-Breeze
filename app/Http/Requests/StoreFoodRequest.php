<?php

namespace App\Http\Requests;

use App\Traits\APIResponseTrait;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class StoreFoodRequest extends FormRequest
{
    use APIResponseTrait;
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
     * @return array<string, ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'title'            => 'required|string',
            'description'      => 'required|string',
            'language_id'      =>'required|integer|exists:languages,id',
            'food_category_id' => 'required|integer|exists:food_categories,id',
        ];
    }
    protected function failedValidation(Validator $validator): void
    {
        $errorMessage = $validator->errors()->all();
        $errorMessage = (string) array_pop($errorMessage);
        throw new HttpResponseException(
            response: $this->unprocessableResponse(
                $errorMessage
            )
        );
    }
}

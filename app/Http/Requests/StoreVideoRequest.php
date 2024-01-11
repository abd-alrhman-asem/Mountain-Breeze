<?php

namespace App\Http\Requests;

use App\Traits\APIResponseTrait;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;


class StoreVideoRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'category_id'   => 'integer|nullable',
            'video'         => 'required|file|mimetypes:video/mp4',
        ];
    }
    protected function failedValidation(Validator $validator): void
    {
        $errorMessage = $validator->errors()->all();
        $errorMessage = (string) array_pop($errorMessage);
        throw new HttpResponseException(
            response: $this->errorResponse(
                $errorMessage,
                422
            )
        );
    }
}

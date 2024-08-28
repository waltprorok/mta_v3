<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\Response;

class SendMessageRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'to' => 'required',
            'subject' => 'required|min:2',
            'body' => 'required|min:3',
        ];
    }

    public function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()
            ->json(['error' => $validator->errors()], Response::HTTP_UNAUTHORIZED)
        );
    }
}

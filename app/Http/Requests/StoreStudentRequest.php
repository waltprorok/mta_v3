<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\Response;

class StoreStudentRequest extends FormRequest
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
            'first_name' => 'required|string|max:50',
            'last_name' => 'required|string|max:50',
            'email' => 'required_if:add_parent,false|email|max:50|nullable',
            'phone' => 'max:32',
            'add_parent' => 'boolean',
            'parent_first_name' => 'required_if:add_parent,true|string|max:50|nullable',
            'parent_last_name' => 'required_if:add_parent,true|string|max:50|nullable',
            'parent_email' => 'required_if:add_parent,true|email|max:50|nullable',
            'status' => 'required|int|max:4',
        ];
    }

    public function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()
            ->json(['error' => $validator->errors()], Response::HTTP_UNAUTHORIZED)
        );
    }
}

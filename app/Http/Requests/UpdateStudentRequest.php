<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateStudentRequest extends FormRequest
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
            'email' => 'string|email|max:50|nullable',
            'phone' => 'string|max:30|nullable',
            'instrument' => 'string|nullable',
            'level' => 'string|nullable',
            'parent_first_name' => 'required_with:parent_email|string|max:50|nullable',
            'parent_last_name' => 'required_with:parent_email,|string|max:50|nullable',
            'parent_email' => 'required_with:parent_first_name|required_with:parent_last_name|email|nullable',
            'parent_phone' => 'string|max:30|nullable',
            'address' => 'string|max:100|nullable',
            'address_2' => 'string|max:100|nullable',
            'city' => 'string|max:50|nullable',
            'state' => 'string|max:50|nullable',
            'zip' => 'integer|digits:5|nullable',
        ];
    }
}

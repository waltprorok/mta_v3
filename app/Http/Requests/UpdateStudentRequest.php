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
            'email' => 'required|string|email|max:50|nullable',
            'phone' => 'string|max:30|nullable',
            'parent_email' => 'string|email|max:255|nullable',
            'zip' => 'integer|digits:5|nullable',
            'instrument' => 'string|nullable',
            'level' => 'string|nullable',
        ];
    }
}

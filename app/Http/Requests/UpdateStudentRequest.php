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
            'email' => 'string|email|max:100|nullable',
            'phone' => 'string|max:30|nullable',
            'instrument' => 'string|nullable',
            'level' => 'string|nullable',
            'auto_schedule' => 'boolean',
            'parent_first_name' => 'required_with:parent_email|string|max:50|nullable',
            'parent_last_name' => 'required_with:parent_email|string|max:50|nullable',
            'parent_email' => 'required_with:parent_first_name|required_with:parent_last_name|email|nullable',
            'parent_phone' => 'string|max:30|nullable',
            'at_home' => 'required_without:at_studio|boolean',
            'at_studio' => 'required_without:at_home|boolean',
            'address' => 'string|max:100|nullable|required_if:at_home,1',
            'address_2' => 'string|max:100|nullable',
            'city' => 'string|max:50|nullable|required_if:at_home,1',
            'state' => 'string|max:50|nullable|required_if:at_home,1',
            'zip' => 'string|max:10|nullable|required_if:at_home,1',
        ];
    }
}

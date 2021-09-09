<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TeacherStoreSettings extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'teacher_id' => 'required|integer',
            'studio_name' => 'required|string|max:255',
            'first_name' => 'required|string|max:100',
            'last_name' => 'required|string|max:100',
            'address' => 'required|string|max:255',
            'address_2' => 'max:120',
            'city' => 'required|string|max:100',
            'state' => 'required|string|max:50',
            'zip' => 'required|integer|digits:5',
            'email' => 'required|string|email|max:255|unique:teachers',
            'phone' => 'required|string|max:20',
            'logo' => 'mimes:jpeg,jpg,png|max:3400'
        ];
    }
}

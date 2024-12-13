<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreTeacherSettingsRequest extends FormRequest
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
            'studio_name' => 'required|string|max:255',
            'first_name' => 'required|string|max:100',
            'last_name' => 'required|string|max:100',
            'address' => 'required|string|max:255',
            'address_2' => 'max:120',
            'city' => 'required|string|max:100',
            'state' => 'required|string|max:50',
            'zip' => 'required|string|max:10',
            'email' => 'required|string|email|max:100',
            'phone' => 'required|string|max:20',
            'logo' => 'mimes:jpeg,jpg,png|max:3400'
        ];
    }
}

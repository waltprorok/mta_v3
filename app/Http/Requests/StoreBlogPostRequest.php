<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreBlogPostRequest extends FormRequest
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
            'title' => 'required|string|max:100',
            'slug' => 'required|string|max:100',
            'body' => 'required',
            'image' => 'mimes:jpg,png,jpeg,gif,svg|max:2048',
            'released_on' => 'required',
        ];
    }
}

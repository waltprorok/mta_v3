<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreScheduleApptRequest extends FormRequest
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
            'student_id' => 'required|string',
            'title' => 'required|string',
            'start_date' => 'required|string',
            'start_time' => 'required|string',
            'end_time' => 'required|string',
            'recurrence' => 'required|string'
        ];
    }
}

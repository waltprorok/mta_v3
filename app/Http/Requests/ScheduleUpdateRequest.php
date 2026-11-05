<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\Response;

class ScheduleUpdateRequest extends FormRequest
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
            'id' => 'required|integer|exists:lessons,id',
            'student_id' => 'required|integer|exists:students,id',
            'billing_rate_id' => 'required|integer|exists:billing_rates,id',
            'title' => 'required|string',
            'color' => 'required|string',
            'start_date' => 'required|date_format:Y-m-d',
            'start_time' => 'nullable|date_format:H:i:s',
            'end_time' => 'nullable|integer|in:15,30,45,60',
            'interval' => 'nullable|integer|in:15,30,45,60',
            'recurrence' => 'required|string',
            'notes' => 'nullable|string',
            'status' => 'nullable|string',
        ];
    }

    public function failedValidation(Validator $validator): void
    {
        throw new HttpResponseException(response()
            ->json(['error' => $validator->errors()], Response::HTTP_UNPROCESSABLE_ENTITY)
        );
    }
}

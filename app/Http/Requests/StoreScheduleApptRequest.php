<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\Response;

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
            'student_id' => 'required|integer|exists:students,id',
            'billing_rate_id' => 'required|integer|exists:billing_rates,id',
            'title' => 'required|string',
            'color' => 'required|string',
            'start_date' => 'required|string',
            'start_time' => 'required|string',
            'end_time' => 'required|string',
            'recurrence' => 'required|string'
        ];
    }

    public function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()
            ->json(['error' => $validator->errors()], Response::HTTP_UNAUTHORIZED)
        );
    }
}

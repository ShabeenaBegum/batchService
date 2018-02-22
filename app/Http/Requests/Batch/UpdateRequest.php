<?php

namespace App\Http\Requests\Batch;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateRequest extends FormRequest
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
            'id' => 'bail|required',
            'course_plan_id' => 'sometimes',
            'start_date' => 'sometimes|date|after_or_equal:today',
            'status'  => ['sometimes',
                Rule::in(['pending', 'completed','yet_to_start'])],
            'mentor' => 'sometimes|nullable|string',
            'days.day' => 'sometimes|required_with:days.time',
            'days.time' => 'sometimes|required_with:days.day',
            'location.city' => 'required_if:mode_of_training,offline',
            'location.country' => 'required_if:mode_of_training,offline',
            'batch_urgency' => ['sometimes',
                Rule::in(['available', 'seats_filling_fast','already_full','moderately_full'])],

        ];
    }
}

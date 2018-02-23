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
//            '_id' => 'bail|required',
            'course_plan_id' => 'required',
            'start_date' => 'sometimes|date|after_or_equal:today',
            'status'  => ['required',
                Rule::in(['pending', 'completed','yet_to_start'])],
            'mentor' => 'sometimes|nullable|string',
            'days.day' => 'sometimes|required_with:days.time',
            'days.time' => 'sometimes|required_with:days.day',
            'location.city' => 'required_if:mode_of_training,offline',
            'location.country' => 'required',
            'batch_urgency' => ['sometimes',
                Rule::in(['available', 'seats_filling_fast','already_full','moderately_full'])],
            'mock_interview' => 'required',
            'mode_of_training' =>'required',
            'course_plan_id' => [
                function($attribute, $value, $fail) {
                    if ($value != $this->batch['course_plan_id']) {

                        if($this->batch['status'] != "yet_to_start"){
                            return $fail($attribute.' can not be changed as batch status is not yet_to_start.');
                        }
                    }
                }
            ],
            'course_session_details' => 'required_with:course_plan_id|array',
            'course_session_details.modules' => 'required_with:course_plan_id|array'

        ];
    }
}

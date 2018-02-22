<?php

namespace App\Http\Requests\Batch;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CreateRequest extends FormRequest
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
            'course_plan_id' => 'required',
            'course_id'      => 'required',
            'status'         => ['required',
                Rule::in(['pending', 'completed','yet_to_start'])],
            'batch_urgency'    => 'required',
            'location.city' => 'required_if:mode_of_training,offline',
            'location.country' => 'required',
            'start_date' => 'required|date|after_or_equal:today',
            'duration' => 'required|integer',
            'mentor' => 'nullable|string',
            'days' => 'required|array',
            'batch_reference_name' => 'nullable',
            'reference_sem_name'   => 'nullable',
            'course_session_details.*' =>  'required|array',
        ];
    }
}

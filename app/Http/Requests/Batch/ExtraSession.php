<?php

namespace App\Http\Requests\Batch;

use Illuminate\Foundation\Http\FormRequest;

class ExtraSession extends FormRequest
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
        return  [
            'session_heading' => 'required',
            'session_topics' => 'required|array',
            'requested_by' => 'required',
            'after_session_id' => 'sometimes',
            'session_date' => 'sometimes',
            'session_time' => 'sometimes',
        ];
    }
}

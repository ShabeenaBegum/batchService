<?php

namespace App\Http\Requests\Content;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ContentRequest extends FormRequest
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
            'session_id' => 'required',
            'enroll_id'  => 'required',
            'user_id'    => 'required',
            'batch_id'    => 'required',
            'content_id' => 'required',
            'submission_link' => 'required|url',
            'content_type' => ['required',
                Rule::in([config('constant.content.assignments'), config('constant.content.projects')])],
            'submission_id' => 'required'
        ];
    }
}

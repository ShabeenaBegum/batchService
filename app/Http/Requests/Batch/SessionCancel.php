<?php

namespace App\Http\Requests\Batch;

use Illuminate\Foundation\Http\FormRequest;

class SessionCancel extends FormRequest
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
            'change_date' => 'required|boolean',
            'requested_by' => 'sometimes',
            'approved_by' => 'required',
            'reason' => 'required'
        ];
    }
}

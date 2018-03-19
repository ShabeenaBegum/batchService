<?php

namespace App\Http\Requests;

use App\Student\Models\StudentBatch;
use Illuminate\Foundation\Http\FormRequest;

class DueSubmissionSession extends FormRequest
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
            "enroll_id" => ["sometimes",
                function($attribute, $value, $fail) {
                    $enroll_id_active = StudentBatch::where("enroll_id",$value)->first();
                    if ($enroll_id_active['status'] != config('constant.batch.status.active')) {
                        return $fail($attribute.' is not ACTIVE');
                    }
                }],
            "session_id"  => "required"
        ];
    }
}

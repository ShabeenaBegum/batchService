<?php

namespace App\Http\Requests\Content;

use App\Student\Models\StudentBatch;
use Illuminate\Foundation\Http\FormRequest;

class DueSubmissionRequest extends FormRequest
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
            "enroll_id" => ["required_without:batch_id",
                function($attribute, $value, $fail) {
                    $enroll_id_active = StudentBatch::where("enroll_id",$value)->first();
                    if ($enroll_id_active['status'] != config('constant.batch.status.active')) {
                        return $fail($attribute.' is not ACTIVE');
                    }
                }],
            "batch_id"  => "required_without:enroll_id"
        ];
    }
}

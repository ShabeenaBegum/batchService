<?php

namespace App\Http\Requests\StudentBatch;

use App\Student\Models\StudentBatch;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;

class Assign extends FormRequest
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
            "user_id" => "required",
            "batch_id" => "required|exists:batches,_id"
        ];
    }

    public function withValidator(Validator $validator)
    {
        $validator->after(function (Validator $validator) {
            $record = StudentBatch::where([
                "enroll_id" => $this->route("enroll"),
                "batch_id" => request('batch_id'),
                "status" => config('constant.Student_batch.status.active')
            ])->exists();
            if ($record) {
                $validator->errors()->add('enroll_id', 'This Enroll is already assigned to batch');
            }
        });
    }

}

<?php

namespace App\Http\Requests\Batch;

use Illuminate\Foundation\Http\FormRequest;

class Transfer extends FormRequest
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
            "from_batch" => "required|exists:batches,_id|different:to_batch",
            "to_batch" => "required|exists:batches,_id|different:from_batch",
            "reason" => "required"
        ];
    }
}

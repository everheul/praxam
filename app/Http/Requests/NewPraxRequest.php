<?php

/**
 * Handles the form in examuser.create
 * Args:  "exam_id", "scene_count", "scene_type", "question_type". All are numeric and positive (>=0).
 */

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class NewPraxRequest extends FormRequest
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
            "exam_id" => 'required|numeric|min:0|max:9999',
            "scene_count" => 'required|numeric|min:0|max:25',
            "scene_type" => 'required|numeric|min:0|max:2',
            "question_type" => 'required|numeric|min:0|max:3'
        ];
    }
}

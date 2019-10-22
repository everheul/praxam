<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class NewSceneRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize() {
        //- todo
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules() {
        return [
            'scene_type_id' => 'required|numeric|min:1|max:2',
            'head' => 'required|string|min:1|max:191',
            'exam_id' => 'required|numeric|min:1',
        ];
    }
}

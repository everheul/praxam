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
            'exam_id' => 'numeric|min:1',
            'scene_id' => 'numeric|min:1',
            'is_public' => 'numeric',
            'scene_type_id' => 'required|numeric|min:1|max:2',
            'head' => 'required|string|min:1|max:191',
            "text" => "nullable|string",
            "instructions" => "nullable|string",
            "newimage" => 'nullable|image|mimes:jpeg,png,jpg,bmp,gif,svg|max:2048',
        ];
    }

    /**
     * Get only the models' updatable data, without the image.
     *
     * @return array
     */
    public function getData() {
        return $this->only(['head','scene_type_id','text','instructions','is_public']);
    }

}

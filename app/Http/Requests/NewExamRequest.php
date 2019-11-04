<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class NewExamRequest extends FormRequest
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
            "name" => "required|string|max:191",
            "head" => "nullable|string|max:191",
            "intro" => "required|string|max:1024",
            "text" => "nullable|string",
            "newimage" => 'nullable|image|mimes:jpeg,png,jpg,bmp,gif,svg|max:2048',
        ];
    }

    /**
     * Get only the models' data from the request, without the image.
     *
     * @return array
     */
    public function getData() {
        return $this->only(['name', 'head', 'intro', 'text']);
    }

}

<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Auth;

class NewQuestionRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Auth::check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = [
            'scene_id' => 'required|numeric|min:1',
            'question_type_id' => 'required|numeric|min:1|max:3',
            'head' => 'required|string|min:0|max:191',
            'text' => 'required|string|min:0|max:5000',
            'explanation' => 'required|string|min:0|max:5000',
            'points' => 'required|numeric|min:1|max:10',
        ];

        return $rules;
    }
    
    /**
     * Get the request's data from the request.
     *
     * 
     * @return array
     */
    public function getData() {
        $data = $this->only(['scene_id', 'question_type_id', 'order', 'head', 'text', 'explanation', 'points']);
        return $data;
    }

}
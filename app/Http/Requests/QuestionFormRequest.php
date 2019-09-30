<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Auth;

class QuestionFormRequest extends FormRequest
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
            'scene_id' => 'nullable',
            'question_type_id' => 'nullable',
            'order' => 'required',
            'head' => 'nullable|string|min:0|max:191',
            'text' => 'nullable|string|min:0|max:5000',
            'explanation' => 'nullable|string|min:0|max:5000',
            'points' => 'nullable',
            'answer_count' => 'nullable|numeric|min:0',
        ];

        return $rules;
    }
    
    /**
     * Get the request's data from the request.
     *
     * 
     * @return array
     */
    public function getData()
    {
        $data = $this->only(['scene_id', 'question_type_id', 'order', 'head', 'text', 'explanation', 'points', 'answer_count']);
        
        
        
        return $data;
    }

}
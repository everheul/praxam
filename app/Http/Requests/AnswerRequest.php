<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AnswerRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * From the manual: If you plan to have authorization logic in another part of your application, return true from the authorize method.
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
            'userquestion' => 'required|integer',
            'answer' => 'required|array',
            'useraction' => 'required|string',
        ];
    }
}

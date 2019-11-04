<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AnswerController extends Controller
{
    public function __construct() {
        //$this->middleware('auth');
    }

    /**
     * todo: auth!
     *
     * @return \Illuminate\Http\Response
     */
    public function store(NewAnswerRequest $request, $exam_id, $scene_id, $question_id) {
        dd($request);
    }
    
}

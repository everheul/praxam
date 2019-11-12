<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\NewAnswerRequest;
Use App\Models\Answer;
Use App\Models\Question;
use DB;

class AnswerController extends Controller
{
    public function __construct() {
        $this->middleware('auth');
        $this->middleware('exam_owner');
    }

    /**
     * todo: auth in NewAnswerRequest?
     *
     * @return \Illuminate\Http\Response
     */
    public function store(NewAnswerRequest $request, $exam_id, $scene_id, $question_id) {
        
        // todo: check id's & user
        
        //- create the answers:
        $atxt = preg_split('/\r\n|\n|\r/',$request->get('answertxt'));
        $order = 1;
        foreach($atxt as $txt) {
            $s = trim($txt);
            if (!empty($s)) {
                $answer = new Answer(['text' => $s, 'question_id' => $question_id, 'order' => $order++]);
                $answer->save();
            }
        }
        //- update answer_count
        $question = Question::findOrFail($question_id);
        $this->calcAnswerCount($question);
        /*
        if ($order > 1) {
            DB::table('questions')
            ->where('id', $question_id)
            ->update(array('answer_count' => DB::raw('(SELECT COUNT(*) FROM answers WHERE question_id = ' . $question_id . ')')));
        }
        */
        if ($request->has('save_show')) {
            //return redirect(url("/exam/$exam_id/scene/$scene_id/question/{$question->id}/show"));
            return redirect()->route( 'exam.scene.question.show', ['exam_id' => $exam_id, 'scene_id' => $scene_id, 'question_id' => $question_id] );
        } elseif ($request->has('save_stay')) {
            return redirect()->route( 'exam.scene.question.answers', ['exam_id' => $exam_id, 'scene_id' => $scene_id, 'question_id' => $question_id] );
        } else {
            // ??
            dd('form error');
        }
    }

    public function destroy(Request $request, $exam_id, $scene_id, $question_id, $answer_id) {
        // todo: auth, check id's
        $answer = Answer::with('question')->findOrFail($answer_id);
        $answer->delete();
        $this->calcAnswerCount($answer->question);
        return redirect()->route( 'exam.scene.question.answers', ['exam_id' => $exam_id, 'scene_id' => $scene_id, 'question_id' => $question_id] );
    }

    /**
     * called on store and destroy.
     * todo: should be an event?
     *
     * @param Question $question
     */
    private function calcAnswerCount(Question $question) {
        $question->answer_count = DB::table('answers')
            ->where('question_id',$question->id)
            ->whereNull('deleted_at')
            ->count();
        $question->save();
    }


}

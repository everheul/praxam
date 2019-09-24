<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use App\Models\UserExam;
use App\Models\UserScene;
use App\Models\UserQuestion;
use App\Models\UserAnswer;
use App\Models\Answer;
use App\Models\Question;

class AjaxController extends Controller
{
    private $ret = "OK";

    public function __construct() {
        $this->middleware('auth');
    }

    public function handle(Request $request) {
        $user = $request->user();
        if (!empty($user)) {
            switch ($request->action) {
                case 'ANSWER': /* practice exam */
                    $this->saveAnswers($request->all());
                    break;
                case 'IGNORE': /* admin preview (/scene/show) */
                    $this->answerToDisk($user->id, $request->all());
                    break;
                default:
                    $this->ret = "Unknown Action: " . $request->action;
            }
            return $this->ret;
        }
        return("ERROR: UNAUTHORISED USER");
    }

    /**
     *
     * @param $data
     */
    private function saveAnswers($data) {

        // this was checked on the client also
        if (empty($data['answers'])) {
            $this->ret = "No answer selected.";
            return;
        }

        // get the userscene
        $us = UserScene::where('userexam_id', '=', $data['userexam'])
                ->where('order', '=', $data['order'])
                ->where('scene_id', '=', $data['scene'])
                ->first();
        if (empty($us)) {
            $this->ret = "This Practice Exam Scene was not found.";
            return;
        }

        // get the userquestion
        $uq = UserQuestion::where('userscene_id', '=', $us->id)
                ->where('question_id', '=', $data['question'])
                ->first();
        if (empty($uq)) {
            $this->ret = "This Practice Exam Question was not found.";
            return;
        } elseif (!is_null($uq->result)) {
            $this->ret = "This Question is Locked.";
            return;
        }

        // get the question with its answers
        $question = Question::where('id', '=', $uq->question_id)->with('answers')->first();
        if (empty($question)) {
            $this->ret = "This Question was not found.";
            return;
        }

        //- store the selected answers
        foreach($data['answers'] as $order => $aid) {
            (new UserAnswer())->create(['userquestion_id' => $uq->id, 'answer_id' => intval($aid), 'order' => $order + 1]);
        }

        //- calc and store the result
        $uq->calcResult($question, $data['answers']);
        $us->checkResult();
        //- todo: $data['userexam'] -> checkResult()
    }

    private function answerToDisk($user, $data) {
        $u = var_export($user,true);
        $d = var_export($data,true);
        Storage::disk('local')->put('\ajax_result.txt', "USER: $u\n\n\nDATA: $d\n");
    }
}










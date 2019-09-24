<?php

/** UserQuestionController
 *  
 * Handle the POST of a question form, 
 * most often containing the answer(s) given by a user doing a test.
 */

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\UserExam;
use App\Models\Scene;
use App\Models\UserScene;
use App\Helpers\Sidebar;

class UserQuestionController extends Controller
{
    public function __construct() {
        $this->middleware('auth');
    }

    /**
     * Check the 'action' of the form.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $order  The order of the userscene in this userexam. check?
     * @return \Illuminate\Http\Response
     */
    public function userAnswer(Request $request, $order)
    {
        $user = $request->user();
        if (!empty($user)) {
            switch ($request->action) {
                case 'ANSWER': /* user test */
                    $this->saveAnswer($user, $request->all());
                    break;
                case 'IGNORE': /* admin preview (/scene/show) */
                    $this->answerToDisk($user, $request->all());
                    break;
                default:
                    report("userAnswer, Unknown Action: " . $request->action);
            }
        }
        report("userAnswer: UNAUTHORISED USER");
    }

    /**
     * Store the answer and calc the result
     * 
     * @param \App\Http\Controllers\User $user
     * @param array $data
     * @return void
     */
    private function saveAnswer(User $user, Array $data) {

        if (empty($data['answers'])) {
            report("saveAnswer: No answer selected.");
            return;
        }
        
        // load the userexam
        $user_exam = UserExam::where('id', '=', $data['userexam'])->first();
        
        if (empty($user_exam)) {
            report("saveAnswer: This UserExam was not found. User: " . $user->id . ", userexam: " . $data['userexam']);
            return;
        }
        
        if ($user_exam->user_id != $user->id) {
            report("saveAnswer: User ID does not match. User: " . $user->id . ", exam user: ". $user_scene->userexam->user_id);
            return;
        }
        
        // load the userscene
        $user_scene = UserScene::where('userexam_id', '=', $data['userexam'])
                ->where('order', '=', $data['order'])
                ->where('scene_id', '=', $data['scene'])
                ->first();
        
        if (empty($user_scene)) {
            report("saveAnswer: This Practice Exam Scene was not found. User: " . $user->id . ", userexam: " . $data['userexam']);
            return;
        }

        // load the answered userquestion
        $user_question = UserQuestion::where('userscene_id', '=', $user_scene->id)
                ->where('question_id', '=', $data['question'])
                ->first();
        
        if (empty($user_question)) {
            report("saveAnswer: UserQuestion not found. User: " . $user->id . ", userscene: " . $user_scene->id);
            return;
        } 
        
        // It may be answered only once
        if (!is_null($user_question->result)) {
            report("saveAnswer: UserQuestion is Locked. User: " . $user->id . ", userquestion: " . $user_question->id);
            return;
        }

        // get the question with its answers
        $question = Question::where('id', '=', $data['question'])
                ->with('answers')
                ->first();
        
        if (empty($question)) {
            report("saveAnswer: Question not found. User: " . $user->id . ", userquestion: " . $user_question->id);
            return;
        }

        //- store the selected answers
        foreach($data['answers'] as $order => $aid) {
            (new UserAnswer())->create(['userquestion_id' => $user_question->id, 'answer_id' => intval($aid), 'order' => $order + 1]);
        }

        //- calc and store the result
        $user_question->calcResult($question, $data['answers']);
        $user_scene->checkResult();
        $user_exam->checkResult();
    }
    
    /**
     * 
     * @param type $user
     * @param type $data
     */
    private function answerToDisk($user, $data) {
        $u = var_export($user,true);
        $d = var_export($data,true);
        Storage::disk('local')->put('\post_result.txt', "USER: $u\n\n\nDATA: $d\n");
    }
    
}

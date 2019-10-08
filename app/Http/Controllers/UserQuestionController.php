<?php

/** UserQuestionController
 *  
 * Handle the POST of a question form, 
 * most often containing the answer(s) given by a user doing a test.
 */

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\UserQuestion;
use App\Models\Question;
use App\Models\UserAnswer;
use App\Models\User;
use App\Models\Scene;
use App\Models\UserScene;
use App\Helpers\Sidebar;
use Illuminate\Support\Facades\Log;
use DB;
use Illuminate\Support\Facades\Storage;

class UserQuestionController extends Controller
{
    private $prax_id;
    private $scene_order;

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
    public function userAnswer(Request $request)
    {
        //dd($request);
        $user = $request->user();
        if (!empty($user)) {
            switch ($request->useraction) {
                case 'ANSWER': /* user test */
                    $is_correct = $this->saveAnswer($user, $request->only("userquestion", "answer"));
                    if ($is_correct) {
                        return redirect("/prax/" . $this->prax_id . "/scene/" . $this->scene_order . "/next");
                    } else {
                        return redirect("/prax/" . $this->prax_id . "/scene/" . $this->scene_order);
                    }
                    break;
                case 'IGNORE': /* admin preview */
                    $this->answerToDisk($user, $request->all());
                    return redirect("/exam/" . $request->get('exam') . "/scene/");
                    break;
                default:
                    Log::warning("userAnswer, Unknown Action: " . $request->action);
            }
        } else {
            Log::warning("userAnswer: UNAUTHORISED USER");
        }
    }

    /**
     * Store the answer and calc the result
     * 
     * @param User $user
     * @param array $data
     * @return void
     */
    private function saveAnswer(User $user, Array $data) {

        if (empty($data['answer'])) {
            Log::warning("saveAnswer: No answer selected.");
            return;
        }
        
        if (empty($data['userquestion'])) {
            Log::warning("saveAnswer: No userquestion id in POST!?.");
            return;
        }

        //- load the userquestion
        $user_question = UserQuestion::where('id', '=', $data['userquestion'])->with('userscene')->with('userscene.userexam')->first();

        if (empty($user_question)) {
            Log::warning("saveAnswer: UserQuestion {$data['userquestion']} not found. User: {$user->id}." );
            return;
        }

        $user_scene = $user_question->userscene;

        if (empty($user_scene)) {
            Log::warning("saveAnswer: This Practice Exam Scene was not found. User: {$user->id}, userquestion: $user_question");
            return;
        }

        $user_exam = $user_question->userscene->userexam;

        if (empty($user_exam)) {
            Log::warning("saveAnswer: This UserExam was not found. User: {$user->id}, userscene: $user_scene");
            return;
        }

        $this->prax_id = $user_exam->id;
        $this->scene_order = $user_scene->order;

        if ($user_exam->user_id != $user->id) {
            Log::warning("saveAnswer: User ID does not match. User: " . $user->id . ", exam user: ". $user_exam->user_id);
            return;
        }

        // It may be answered only once
        if (!is_null($user_question->result)) {
            Log::warning("saveAnswer: UserQuestion is Locked. User: " . $user->id . ", userquestion: " . $user_question->id);
            return;
        }

        // get the question with its answers
        $question = Question::where('id', '=', $user_question->question_id)
                ->with('answers')
                ->first();
        
        if (empty($question)) {
            Log::warning("saveAnswer: Question not found. User: " . $user->id . ", userquestion: " . $user_question->id);
            return;
        }

//dd($data['answer'],$user_question->id);
//
        //- store the selected answers
        foreach($data['answer'] as $order => $aid) {
            (new UserAnswer())->create(['userquestion_id' => $user_question->id, 'answer_id' => intval($aid), 'order' => $order + 1]);
        }

        //- calc and store the result
        $is_correct = $user_question->calcResult($question, $data['answer']);
        $user_scene->checkResult();
        $user_exam->checkResult();

        return $is_correct;
    }
    
    /**
     * 
     * @param type $user
     * @param type $data
     */
    private function answerToDisk($user, $data) {
        $u = var_export($user,true);
        $d = var_export($data,true);
        //- ?? doesn't do anything ??
        Storage::disk('local')->put('post_result.txt', "USER: $u\n\n\nDATA: $d\n");
        //dd($user, $data);
    }
    
}

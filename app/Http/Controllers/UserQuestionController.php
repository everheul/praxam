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
    //private $user_question;
    //private $prax_id;
    //private $scene_order;
    //private $question_order;

    public function __construct() {
        $this->middleware('auth');
    }

    /**
     * Check the 'action' of the form.
     * todo: validate
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
                    // todo: validate
                    $data = $request->only("userquestion", "answer");
                    $user_question = UserQuestion::where('id', '=', $data['userquestion'])->with('userscene','userscene.userexam','userscene.scene')->first();
                    if (empty($user_question)) {
                        // todo
                    }
                    $is_correct = $this->saveAnswer($user, $user_question);
                    if ($is_correct) {
                        $this->nextQuestion($request, $user_question->id, $user_question->userscene->order, $user_question->order);
                    } else {
                        //- todo: just send reload?
                        switch ($user_question->userscene->scene->scene_type_id) {
                            case 1:
                                return redirect("/prax/" . $user_question->id . "/scene/" . $user_question->userscene->order);
                            case 2:
                            default:
                                return redirect("/prax/" . $user_question->id . "/scene/" . $user_question->userscene->order . '/question/' . $user_question->order);
                        }
                    }
                    break;
                case 'IGNORE': /* admin preview */
                    $this->answerToDisk($user, $request->all());
                    //- show list
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
    private function saveAnswer(User $user, UserQuestion $userquestion) {

        if ($userquestion->userscene->userexam->user_id != $user->id) {
            Log::warning("saveAnswer: User ID does not match. User: " . $user->id . ", exam user: ". $userquestion->userscene->userexam->user_id);
            // todo: redirect??
            return false;
        }

        // It may be answered only once
        if (!is_null($user_question->result)) {
            Log::warning("saveAnswer: UserQuestion is Locked. User: " . $user->id . ", userquestion: " . $user_question->id);
            // todo: redirect??
            return false;
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
     * @param Request $request
     * @param $prax_id
     * @param $s_order
     * @param int $q_order
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function nextQuestion(Request $request, $prax_id, $s_order, $q_order = 1) {
        $next = DB::table('userscenes')
            ->join('userquestions', 'userscenes.id', '=', 'userquestions.userscene_id')
            ->whereRaw("userquestions.result IS NULL AND (userscenes.order > $s_order OR (userscenes.order = $s_order AND userquestions.order > $q_order))")
            ->orderBy('userscenes.order')
            ->orderBy('userquestions.order')
            ->select('userscenes.order AS s_order','userquestions.order AS q_order')
            ->first();
        if (empty($next)) {
            $next = DB::table('userscenes')
                ->join('userquestions', 'userscenes.id', '=', 'userquestions.userscene_id')
                ->whereRaw("userquestions.result IS NULL")
                ->orderBy('userscenes.order')
                ->orderBy('userquestions.order')
                ->select('userscenes.order AS s_order','userquestions.order AS q_order')
                ->first();
        }
        if (empty($next)) {
            //- no unanswered questions left; test finished. Show result:
            return redirect(url("/prax/$prax_id"));
        } else {
            return redirect(url("/prax/$prax_id/scene/{$next->s_order}/question/{$next->q_order}"));
        }
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

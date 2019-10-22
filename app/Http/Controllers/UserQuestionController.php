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
    public function userAnswer(Request $request) {

        //$data = $request->only("userquestion", "answer");
        $data = $request->validate([
            'userquestion' => 'required|integer',
            'answer' => 'required|array',
        ]);

        switch ($request->useraction) {

            case 'ANSWER': /* user answers */
                $user = $request->user();

                //- Load the data we need:
                //DB::enableQueryLog();
                $userquestion = UserQuestion::where('id', $data['userquestion'])
                    ->with('userscene','userscene.userexam','userscene.scene')
                    ->first();
                //dd(DB::getQueryLog(),$userquestion);

                //- database problem?
                if (empty($userquestion)) {
                    Log::critical("userAnswer: UserQuestion not found! User: " . $user->id . ", UserQuestion: ". $data['userquestion']);
                    // todo: should we add a warning?
                    return redirect("/home");
                }

                //- test ownership: not using middleware here; admin should not answer any questions but his own.
                //- this only happens if done by hackers; if it happens too often it should go to a new middleware class..
                if ($userquestion->userscene->userexam->user_id != $user->id) {
                    Log::warning("saveAnswer: User ID does not match. User: " . $user->id . ", exam user: ". $userquestion->userscene->userexam->user_id);
                    // todo: should we add a warning?
                    return redirect("/home");
                }

                //- for readability
                $userexam_id = $userquestion->userscene->userexam->id;

                // Questions can be answered only once..
                if (!is_null($userquestion->result)) {
                    Log::warning("userAnswer: UserQuestion is Locked. User: " . $user->id . ", userquestion: " . $userquestion->id);
                    return $this->nextQuestion($request, $userexam_id, $userquestion->userscene->order, $userquestion->order);
                }

                if ($this->saveAnswer($user, $userquestion, $data['answer'])) {
                    // correct! try next question:
                    return $this->nextQuestion($request, $userexam_id, $userquestion->userscene->order, $userquestion->order);
                } else {
                    //- false answer, show explanation. todo: just send reload?
                    switch ($userquestion->userscene->scene->scene_type_id) {
                        case 1:
                            return redirect("/prax/" . $userexam_id . "/scene/" . $userquestion->userscene->order);
                        case 2:
                        default:
                            return redirect("/prax/" . $userexam_id . "/scene/" . $userquestion->userscene->order . '/question/' . $userquestion->order);
                    }
                }
                break;

            case 'IGNORE': /* admin preview */
                $this->answerToDisk($request->user(), $data);
                //- show list
                return redirect("/exam/" . $request->get('exam') . "/scene/");
                break;

            default:
                Log::warning("userAnswer, Unknown Action: " . $request->action);
        }
    }

    /**
     * Store the answer and calc the result
     * 
     * @param User $user
     * @param array $data
     * @return bool
     */
    private function saveAnswer(User $user, UserQuestion $userquestion, Array $answers) {

        //- store the chosen answers
        foreach($answers as $order => $aid) {
            (new UserAnswer())->create(['userquestion_id' => $userquestion->id, 'answer_id' => intval($aid), 'order' => $order + 1]);
        }

        //- calc and store the result
        $is_correct = $userquestion->calcResult($answers) > 0;
        $userquestion->userscene->checkResult();
        $userquestion->userscene->userexam->checkResult();
        return $is_correct;
    }

    /**
     * redirect to the next unanswered question in this userexam
     *
     * @param Request $request
     * @param $userexam_id
     * @param $s_order
     * @param int $q_order
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function nextQuestion(Request $request, $userexam_id, $s_order, $q_order = 1) {
        //DB::enableQueryLog();
        $next = DB::table('userscenes')
            ->join('userquestions', 'userscenes.id', '=', 'userquestions.userscene_id')
            ->where('userscenes.userexam_id', $userexam_id)
            ->whereNull('userquestions.result')
            ->whereRaw("(userscenes.order > $s_order OR (userscenes.order = $s_order AND userquestions.order > $q_order))")
            ->orderBy('userscenes.order')
            ->orderBy('userquestions.order')
            ->select('userscenes.order AS s_order','userquestions.order AS q_order')
            ->first();
        //dd(DB::getQueryLog(),$next);
        if (empty($next)) {
            $next = DB::table('userscenes')
                ->join('userquestions', 'userscenes.id', '=', 'userquestions.userscene_id')
                ->where('userscenes.userexam_id', $userexam_id)
                ->whereNull('userquestions.result')
                ->orderBy('userscenes.order')
                ->orderBy('userquestions.order')
                ->select('userscenes.order AS s_order','userquestions.order AS q_order')
                ->first();
        }
        if (empty($next)) {
            //- no unanswered questions left; test finished. Show result:
            return redirect(url("/prax/$userexam_id"));
        } else {
            return redirect(url("/prax/$userexam_id/scene/{$next->s_order}/question/{$next->q_order}"));
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

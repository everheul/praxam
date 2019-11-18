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
use App\Http\Requests\AnswerRequest;

class UserQuestionController extends Controller
{

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
    public function userAnswer(AnswerRequest $request) {

        //$data = $request->only("userquestion", "answer");

        switch ($request->useraction) {

            case 'ANSWER': /* user answers */
                $user = $request->user();

                //- Load the data we need:
                //DB::enableQueryLog();
                $uqid = $request->get('userquestion');
                $userquestion = UserQuestion::where('id', $uqid)
                    ->with('userscene','userscene.userexam','userscene.scene')
                    ->first();
                //dd(DB::getQueryLog(),$userquestion);

                //- database problem?
                if (empty($userquestion)) {
                    Log::critical("userAnswer: UserQuestion not found! User: " . $user->id . ", UserQuestion: ". $uqid);
                    // todo: should we add a warning?
                    return redirect("/home");
                }

                //- test ownership: not using middleware here; admin should not answer any questions but his own.
                //- this only happens if done by hackers; if it happens too often it should go to a new middleware class..
                if ($userquestion->userscene->userexam->user_id != $user->id) {
                    Log::warning("saveAnswer: User ID does not match. User: " . $user->id . ", exam user: ". $userquestion->userscene->userexam->user_id);
                    // todo: should we add a warning? return nothing?
                    return redirect("/home");
                }

                //- for readability
                $userexam_id = $userquestion->userscene->userexam->id;

                // Questions can be answered only once..
                if (!is_null($userquestion->result)) {
                    Log::warning("userAnswer: UserQuestion is Locked. User: " . $user->id . ", userquestion: " . $userquestion->id);
                    return $this->nextQuestion( $userexam_id, $userquestion->userscene->order, $userquestion->order);
                }

                if ($this->saveAnswer($user, $userquestion, $request->get('answer'))) {
                    // correct! try next question:
                    return $this->nextQuestion( $userexam_id, $userquestion->userscene->order, $userquestion->order);
                } else {
                    //- false answer, show explanation.
                    return redirect( url()->previous() );
                }
                break;

            case 'IGNORE': /* admin preview */
                $this->answerToDisk($request->user(), $request->only("userquestion", "answer"));
                //- show list
                return redirect("/exam/" . $request->get('exam') . "/scene/");
                break;

            default:
                Log::warning("userAnswer, Unknown Action: " . $request->action);
                return redirect('/home');
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
     * redirect to the next (or first) unanswered question in this userexam,
     * or to the result page if all were answered.
     *
     * @param Request $request
     * @param $userexam_id
     * @param $s_order
     * @param int $q_order
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function nextQuestion($userexam_id, $s_order, $q_order = 1) {
        // list of all the unanswered questions:
        $list = DB::table('userscenes')
            ->join('userquestions', 'userscenes.id', '=', 'userquestions.userscene_id')
            ->where('userscenes.userexam_id', $userexam_id)
            ->whereNull('userquestions.result')
            ->orderBy('userscenes.order')
            ->orderBy('userquestions.order')
            ->select('userscenes.order AS s_order','userquestions.order AS q_order')
            ->get();

        $qleft = $list->count();
        if ($qleft > 0) {
            $next = $list->first(function($obj) use ($s_order, $q_order) {
                return ($obj->s_order > $s_order) || (($obj->s_order === $s_order) && ($obj->q_order > $q_order));
            });
            if (empty($next)) {
                $next = $list->first(); // go to first un-answered question
            }
            if ($next->q_order > 1) {
                return redirect(url("/prax/$userexam_id/scene/{$next->s_order}/question/{$next->q_order}"));
            } else {
                // don't add the question order in the route if it's the first (or only) question
                return redirect(url("/prax/$userexam_id/scene/{$next->s_order}"));
            }
        } else {
            //- all done, show result
            return redirect(url("/prax/$userexam_id"));
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

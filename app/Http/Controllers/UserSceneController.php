<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\UserExam;
use App\Models\Scene;
use App\Models\UserScene;
use App\Helpers\Sidebar;

class UserSceneController extends Controller
{
    // used to check user-exam ownership once
    private $user_checked;

    public function __construct() {
        $this->middleware('auth');
    }

    /**
     * Display a list of the scenes of this prax?
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        dd(" -= TODO =- ");
    }

    /**
     * Display the scene as part of a test
     * todo: disable input for questions that are locked, and show the chosen answers.
     *
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $prax_id, $order) {

        if (!$this->checkUser($request, $prax_id)) {
            return redirect(url("/home"));
        }

        /*
        //$userScene = $this->loadFullUserscene($prax_id, $order);
        $userScene = UserScene::where('userexam_id','=',$prax_id)->where('order','=',$order)->with('userquestions','userquestions.useranswers')->firstOrFail();
        //$scene = $this->mergeUserscene( $this->loadFullScene($userScene->scene_id), $userScene);
        $scene = $this->mergeUserscene( (new SceneController())->getFullScene($userScene->scene_id), $userScene);
        $userScene->setScene($scene);
        //$nextId = $this->next($userexam, $order);
        */

        if ($praxScene = $this->loadPraxScene($prax_id, $order)) {
            $sidebar = (new SideBar)->practiceExam($prax_id, $order);
            return View('scene.show.type' . $praxScene->scene->scene_type_id,
                [   'sidebar' => $sidebar,
                    'praxScene' => $praxScene,
                    'action' => 'ANSWER',
                ]);
        } else {
            //- userScene not found?? redirect to result
            return redirect(url("/prax/$prax_id/show"));
        }
    }

    /**
     * @param  int  $prax_id
     * @param  int  $order
     * @return  PraxScene|bool
     */
    private function loadPraxScene($prax_id, $order) {
        $userScene = UserScene::where('userexam_id','=',$prax_id)
            ->where('order','=',$order)
            ->with('userquestions','userquestions.useranswers')
            ->firstOrFail();
        if (!empty($userScene)) {
            $scene = (new SceneController())->getFullScene($userScene->scene_id);
            return new PraxScene($scene, $userScene);
        }
        return false;
    }

    /**
     * Select the next scene to display when the user selects '' or 'continue'
     *
     * @param  UserExam  $userexam
     * @param  int  $order
     * @return int
    private function next( $userexam, $order) {
        return ($order < $userexam->scene_count) ? $order + 1 : 0;
    }

    private function loadFullUserscene($userexamId, $order) {
        return UserScene::where('userexam_id','=',$userexamId)->where('order','=',$order)->with('userquestions','userquestions.useranswers')->firstOrFail();
        //return $this->enumResults($prax);
    }

    private function loadFullScene($sceneId) {
        return (new SceneController())->getFullScene($sceneId);
        //return Scene::where('id', '=', $sceneId)->with('questions','questions.answers')->firstOrFail();
    }
*/

    /**
     *  Lock the already answered questions.
     *
     * @param $scene
     * @param $userScene
     **/
    private function mergeUserscene($scene, $userScene) {
        foreach($userScene->userquestions as $userquestion) {
            if (!is_null($userquestion->result)) {
                $question = $scene->questions->find($userquestion->question_id);
                if (!empty($question)) {
                    $question->locked = true;
                    foreach($userquestion->useranswers as $useranswer) {
                        $answer = $question->answers->find($useranswer->answer_id);
                        if (!empty($answer)) {
                            $answer->checked = true;
                            $answer->order = $useranswer->order;
                        }
                    }
                }
            }
        }
        return $scene;
    }

    /**
* Set the UserScenes' result & locked values until locked.
     *
     * @param  UserScene  $prax
     * @return  UserScene
    private function enumResults(UserScene $prax) {
        if (empty($prax->locked)) {
            $tot = 0;
            $locked = 1;
            foreach ($prax->userquestions as $uq) {
                if (is_null($uq->result)) {
                    $locked = 0;
                } else {
                    $tot += $uq->result;
                }
            }
            $prax->locked = $locked;
            $prax->result = $tot;
            $prax->update();
        }
        return $prax;
    }     */

    /**
     * Make sure this userExam was created by THIS user.
     * todo: move to User?
     *
     * @param Request $request
     * @param $prax_id
     * @return bool
     */
    private function checkUser(Request $request, $prax_id)
    {
        if (empty($this->user_checked)) {
            $user_exam = UserExam::where('id', $prax_id)->firstOrFail();
            $this->user_checked = ($user_exam->user_id === $request->user()->id);
            //- todo: log if false
        }
        return $this->user_checked;
    }

}

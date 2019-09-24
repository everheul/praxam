<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\UserExam;
use App\Models\Scene;
use App\Models\UserScene;
use App\Helpers\Sidebar;

class UserSceneController extends Controller
{
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
    public function show($prax_id, $order) {
        $userexam = UserExam::findOrFail($prax_id);
        if (($order > 0) && ($order <= $userexam->scene_count)) {
            $userScene = $this->loadFullUserscene($prax_id, $order);
            $scene = $this->mergeUserscene( $this->loadFullScene($userScene->scene_id), $userScene);
            $nextId = $this->next($userexam, $order);
            $sidebar = (new SideBar)->practiceExam($prax_id, $order);
            return View('scene.show.type' . $scene->scene_type_id,
                [   'sidebar' => $sidebar,
                    'scene' => $scene,
                    'action' => 'ANSWER',
                    'user' => ['exam' => $prax_id, 'order' => $order],
                    'next' => "/prax/$prax_id/scene/$nextId/show"
                ]);
        } else {
            //- redirect to result
            return redirect(url("/prax/$prax_id/show"));
        }
    }

    /**
     * Select the next scene to display when the user selects '' or 'continue'
     *
     * @param  UserExam  $userexam
     * @param  int  $order
     * @return int
     */
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

}

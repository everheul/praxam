<?php

namespace App\Classes\SceneMgr;

use App\Models\Exam;
use App\Models\Scene;
use App\Models\Question;
use App\Models\Answer;
use App\Models\UserExam;
use App\Models\UserScene;
use App\Models\UserQuestion;
use App\Models\UserAnswer;

class SceneMgr
{
    private $stype;

    public function __construct(Scene $scene) {
        $className = 'SceneTypes\SType' . $scene->scene_type_id;
        if (class_exists($className)) {
            $this->stype = new $className($scene);
        }
        else {
            throw new Exception("Invalid SceneType in scene.");
        }
    }

    public function preview($active_question = 0) {

        return $this->stype->preview($active_question);

        /*
        // get all relevant data
        $this->scene->loadMissing('exam', 'sceneType', 'questions', 'questions.answers');

        // create a praxscene without userdata - todo: PraxScene?
        $praxscene = (new PraxScene())->setAdminSceneData($this->scene);

        // scene type class name, db leads
        $stypename = 'SType' . $scene->scene_type_id;

        // show page
        return(new $stypename())->preview($praxscene);

        return View('scene.type' . $scene->scene_type_id . '.show',
            ['sidebar' => (new Sidebar())->sbarSceneShow($scene),
                'pagehead' => 'Scene ' . $this->getSceneOrderOfTotal($exam_id, $scene_id),
                'praxscene' => $praxscene,
                'useraction' => 'IGNORE',
                'exam_id' => $exam_id,
            ]);
        */
    }

}
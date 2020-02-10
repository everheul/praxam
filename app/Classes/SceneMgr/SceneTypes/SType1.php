<?php

namespace App\Classes\SceneMgr\SceneTypes;


class SType1 extends SType implements SceneTypeContract
{

    public function edit(Exam $exam, Scene $scene, $active_question = 1) {

    }

    public function preview() {
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

    public function show(UserExam $exam, UserScene $scene, $active_question = 1) {

    }

}
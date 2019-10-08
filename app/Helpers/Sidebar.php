<?php 

namespace App\Helpers;

use App\Models\Exam;
use App\Models\Scene;
use App\Models\UserScene;
use App\Models\UserExam;
use App\Models\User as User;
use Illuminate\Support\Facades\Auth;
use App\Classes\PraxExam;

class Sidebar
{
    private $blocks = [];

    /**
     * Called from SceneController@index
     * @param Exam $exam
     * @return array
     */
    public function editExamScenes($exam = null) {
        if (!empty($exam)) {
            $id = $exam->id;
            $this->sbarLink('Exams','overview',"/exam");
            $this->sbarBlock($exam->name, $exam->head);
            $this->sbarButton('Start Practice Exam',"/prax/$id/create",'dark');
            $user = Auth::user();
            if (!empty($user) && $user->isAdmin()) {
                $this->sbarHead("Exam", 'Admin Controls');
                $this->sbarButton('Edit Exam', "/exam/$id/edit/", 'dark');
                $this->sbarButton('Cancel',"/exam/$id/show/",'dark');
            }
        }
        return $this->blocks;
    }

    /**
     *
     * @param Exam $exam
     * @return array
     */
    public function examOverview($exam = null)
    {
        if (!empty($exam)) {
            $id = $exam->id;
            $this->sbarLink('Exams','overview',"/exam");
            $this->sbarBlock($exam->name, $exam->head);
            $this->sbarButton('Start Practice Exam',"/prax/$id/create",'dark');
            $user = Auth::user();
            if (!empty($user) && $user->isAdmin()) {
                $this->sbarHead("Exam", 'Admin Controls');
                $this->sbarButton('Edit Exam',"/exam/$id/edit/",'dark');
    //            $this->sbarButton('Delete Exam',"/exam/$id/kill/",'danger'); //- todo:  onclick="return confirm(&quot;Click Ok to delete this Scene.&quot;)"
                $this->sbarButton('Manage Scenes',"/exam/$id/scene/",'dark');
            }
        } else {
            $this->sbarHead('Exams','overview');
            $exams = Exam::get(['id', 'name', 'head']);
            foreach ($exams as $exam) {
                $this->sbarLink($exam->name,$exam->head,"/exam/".$exam->id."/show");
            }
        }
        return $this->blocks;
    }

    public function examEdit($exam) {
        $this->sbarLink('Exams','overview',"/exam");
        $this->sbarBlock($exam->name, $exam->head);
        $id = $exam->id;
        $this->sbarHead("Edit Exam $id", 'Admin Controls');
        $this->sbarButton('Cancel',"/exam/$id/show/",'dark');
        return $this->blocks;
    }

    /**
     * Called from SceneController@show
     * @param Exam $exam
     * @return array
     */
    public function sceneExams($scene) {
        $this->sbarLink($scene->exam->name,$scene->exam->head,"/exam/" . $scene->exam->id . "/show");       
        //- todo: admin / owner check?
        $this->sbarHead('Admin Tools', 'Scene ' . $this->getSceneOrderOfTotal($scene->exam->id, $scene->id));
        $this->sbarButton('Scene List',"/exam/" . $scene->exam->id . '/scene/','dark');
        $this->sbarButton('Next Scene',"/exam/" . $scene->exam->id . '/scene/' . $scene->id . "/next",'dark');
        $this->sbarButton('Create Scene',"/exam/" . $scene->exam->id . '/scene/create','success');
        $this->sbarButton('Edit Scene',"/exam/" . $scene->exam->id . '/scene/' . $scene->id . "/edit",'primary');
        $this->sbarDelete('Delete Scene',"/exam/" . $scene->exam->id . '/scene/' . $scene->id . "/destroy");
        return $this->blocks;
    }

    public function editUserExam($exam) {
        $this->sbarBlock($exam->name, $exam->head);
        $this->sbarHead("Start Exam", '');
        $this->sbarButton('Cancel',"/exam/".$exam->id."/show",'dark');
        return $this->blocks;
    }

    public function practiceExam(UserExam $userexam, $order) {
        $this->sbarBlock($userexam->exam->name, $userexam->exam->head);
        $this->userSceneList($userexam->id, $order);
        $this->sbarScene("View Score","/prax/{$userexam->id}/show","outline-dark");
        return $this->blocks;
    }

    // todo
    public function examResult(PraxExam $praxexam) {
        $exam_id = $praxexam->exam->id;
        $this->sbarBlock($praxexam->exam->name, $praxexam->exam->head);
        $this->sbarButton('Back to Overview',"/exam",'dark');
        $this->sbarButton('New Practice Exam',"/prax/$exam_id/create",'dark');
        //$this->sbarBlock('Started At',$prax->created_at);
        $this->userSceneList($exam_id, 0); // todo: use $praxexam ??
    //    $this->sbarButton('Delete Practice','/prax/'.$prax->id.'/kill','danger');
        return $this->blocks;
    }

    //-- private functions -- synq with view/components/sidebar.blade.php

    private function userSceneList($prax_id, $order) {
        // load all userscenes and their userquestions objects
        $userscenes = UserScene::where('userexam_id','=',$prax_id)->orderBy('order')->get();
        foreach($userscenes as $us) {
            $scorder = $us->order;
            $head = "Scene $scorder";
            $href = "/prax/$prax_id/scene/$scorder";
            $locked = $us->locked;
            $col = $locked ? "info" : "dark";
            if ($order != $scorder) {
                $col = 'outline-' . $col;
            }
            $this->sbarScene($head,$href,$col);
        }
    }

    /**
     * 
     * @param int $exam_id
     * @param int $scene_id
     * @return string
     */
    private function getSceneOrderOfTotal($exam_id, $scene_id) {
        $scenes = Scene::select('id')
                ->where('exam_id', '=', $exam_id)
                ->orderBy('id')
                ->get();
        $idlist = array_flip($scenes->pluck('id')->all());       
        $total = $scenes->count();
        return sprintf("%d of %d", $idlist[$scene_id]+1, $total);
    }

    
    private function sbarHead($head, $text) {
        $this->blocks[] = ['type' => 'sbar-head', 'head' => $head, 'text' => $text];
    }

    private function sbarBlock($head, $text) {
        $this->blocks[] = ['type' => 'sbar-block', 'head' => $head, 'text' => $text];
    }

    private function sbarLink($head, $text, $href) {
        $this->blocks[] = ['type' => 'sbar-link', 'head' => $head, 'text' => $text, 'href' => $href ];
    }

    private function sbarButton($head, $href, $color = 'dark') {
        $this->blocks[] = ['type' => 'sbar-button', 'head' => $head, 'href' => $href, 'color' => $color ];
    }

    private function sbarDelete($head, $href, $msg = 'Are you sure?') {
        $this->blocks[] = ['type' => 'sbar-delete', 'head' => $head, 'href' => $href, 'color' => 'danger', 
            'msg' => "onclick=\"return confirm('$msg')\"" ];
    }

    private function sbarScene($head, $href, $color = 'outline-dark') {
        $this->blocks[] = ['type' => 'sbar-scene', 'head' => $head, 'href' => $href, 'color' => $color ];
    }

}


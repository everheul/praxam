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
     * Called from: ExamController@index
     * @return array
     */
    public function sbarExamIndex() {
        $this->sbarHead('All Exams','overview');
        return $this->blocks;
    }

    /**
     * Called from: ExamController@show
     * @param Exam $exam
     * @return array
     */
    public function sbarExamShow(Exam $exam) {
        $id = $exam->id;
        $this->sbarLink('All Exams','overview',"/exam");
        $this->sbarBlock($exam->name, $exam->head);
        $this->sbarButton('Start Test',"/prax/$id/create",'dark');
        if (Auth::user()->isAdmin()) {
            $this->sbarHead("- = -", 'Admin Controls');
            $this->sbarButton('Edit Exam',"/exam/$id/edit/",'primary');
            $this->sbarButton('Manage Scenes',"/exam/$id/scene/",'primary');
            $this->sbarDelete('Delete Exam',"/exam/$id/destroy");
        }
        return $this->blocks;
    }

    /**
     * Called from SceneController@index
     * @param Exam $exam
     * @return array
     */
    public function sbarSceneIndex($exam) {
        $id = $exam->id;
        $this->sbarLink('All Exams','overview',"/exam");
        $this->sbarBlock($exam->name, $exam->head);
        $this->sbarButton('Start Test',"/prax/$id/create",'dark');
        if (Auth::user()->isAdmin()) {
            $this->sbarHead("- = -", 'Admin Controls');
            $this->sbarButton('Edit Exam', "/exam/$id/edit/", 'primary');
            $this->sbarButton('Cancel',"/exam/$id/show/",'dark');
            $this->sbarDelete('Delete Exam',"/exam/$id/destroy");
        }
        return $this->blocks;
    }

    /**
     * Called from HomeController@index
     */
    public function sbarHomeIndex() {
        $this->sbarLink('All Exams','overview',"/exam");
        $exams = Exam::get(['id', 'name', 'head']);
        foreach ($exams as $exam) {
            $this->sbarLink($exam->name, $exam->head, "/exam/".$exam->id."/show");
        }
        return $this->blocks;
    }

    /**
     * The menu during the test, called from
     *
     * @param  PraxExam  $praxexam
     * @param  int  $order
     * @return  array
     */
    public function practiceExam(PraxExam $praxexam, $order) {
        $this->sbarBlock($praxexam->exam->name, $praxexam->exam->head);
        $this->userSceneList($praxexam, $order);
        $this->sbarScene("View Score","/prax/{$praxexam->userexam->id}/show","outline-dark");
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

    private function getSceneOrderOfTotal($exam_id, $scene_id) {
        $scenes = Scene::select('id')
            ->where('exam_id', '=', $exam_id)
            ->orderBy('id')
            ->get();
        $idlist = array_flip($scenes->pluck('id')->all());
        $total = $scenes->count();
        return sprintf("%d of %d", $idlist[$scene_id]+1, $total);
    }

    public function editUserExam($exam) {
        $this->sbarBlock($exam->name, $exam->head);
        $this->sbarHead("Start Exam", '');
        $this->sbarButton('Cancel',"/exam/".$exam->id."/show",'dark');
        return $this->blocks;
    }

    /**
     * Called from UserExamController@show
     *
     * @param PraxExam $praxexam
     * @return array
     */
    public function examResult(PraxExam $praxexam) {
        $exam_id = $praxexam->exam->id;
        $this->sbarBlock($praxexam->exam->name, $praxexam->exam->head);
        $this->sbarButton('Back to Overview',"/exam",'dark');
        $this->sbarButton('New Practice Exam',"/prax/$exam_id/create",'dark');
        //$this->sbarBlock('Started At',$prax->created_at);
        $this->userSceneList($praxexam, 0);
    //    $this->sbarButton('Delete Practice','/prax/'.$prax->id.'/kill','danger');
        return $this->blocks;
    }

    //-- private functions -- synq with view/components/sidebar.blade.php

    private function userSceneList(PraxExam $praxexam, $order) {
        $userscenes = $praxexam->userexam->userscenes;
        foreach($userscenes as $us) {
            $scorder = $us->order;
            $head = "Scene $scorder";
            $href = "/prax/{$praxexam->userexam->id}/scene/$scorder";
            $col = $us->locked ? "info" : "dark";
            if ($order != $scorder) {
                $col = 'outline-' . $col;
            }
            $this->sbarScene($head,$href,$col);
        }
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


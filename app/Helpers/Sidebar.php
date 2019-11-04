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
        $this->myPracxam();
        return $this->blocks;
    }
    /**
     * Called from: ExamController@show
     * @param Exam $exam
     * @return array
     */
    public function sbarExamShow(Exam $exam) {
        $this->myPracxam();
        $this->allExams();
        $this->examLogo($exam);
        $id = $exam->id;
        $this->sbarButton('Start Test',"/prax/$id/create",'dark');
        if ($exam->canEdit(Auth::user())) {
            $this->sbarHead("<hr />", 'Admin Controls');
            $this->sbarButton('Edit Exam',"/exam/$id/edit/",'primary');
            $this->sbarButton('Manage Scenes',"/exam/$id/scene/",'primary');
            $this->sbarDelete('Delete Exam',"/exam/$id/destroy");
        }
        return $this->blocks;
    }

    public function sbarNoExam() {
        $this->myPracxam();
        $this->allExams();
        return $this->blocks;
    }

    /**
     * Called from SceneController@index
     * @param Exam $exam
     * @return array
     */
    public function sbarSceneIndex($exam) {
        $this->myPracxam();
        $this->allExams();
        $this->examLogo($exam);
        $id = $exam->id;
        $this->sbarButton('Start Test',"/prax/$id/create",'dark');
        if (Auth::user()->isAdmin()) {
            $this->sbarHead("-", 'Admin Controls');
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
        $this->allExams();
        $exams = Exam::get(['id', 'name', 'head']);
        foreach ($exams as $exam) {
            $this->sbarLink($exam->name, $exam->head, "/exam/".$exam->id."/show");
        }
        return $this->blocks;
    }

    /**
     * The menu during the test, called from UserSceneController@show
     *
     * @param  PraxExam  $praxexam
     * @param  int  $order
     * @return  array
     */
    public function practiceExam(PraxExam $praxexam, $order) {
        $this->myPracxam();
        $this->allExams();
        $this->examLogo($praxexam->exam);
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

    public function sceneCreate($exam) {
        $this->myPracxam();
        $this->allExams();
        $this->sbarBlock($exam->name, $exam->head);
        $this->sbarButton('Cancel',"/exam/{$exam->id}/scene",'dark');
        return $this->blocks;
    }

    public function questionCreate(Scene $scene) {
        $this->myPracxam();
        $this->allExams();
        $this->sbarBlock($scene->exam->name, $scene->exam->head);
        $this->sbarButton('Cancel',"/exam/{$scene->exam->id}/scene/{$scene->id}/edit",'dark');
        return $this->blocks;
    }

    public function questionEdit(Scene $scene) {
        $this->myPracxam();
        $this->allExams();
        $this->sbarBlock($scene->exam->name, $scene->exam->head);
        $this->sbarButton('Cancel',"/exam/{$scene->exam->id}/scene/{$scene->id}/edit",'dark');
        return $this->blocks;
    }

    /**
     * Called from SceneController@show
     * @param Exam $exam
     * @return array
     */
    public function sceneShow($scene) {
        $this->myPracxam();
        $this->allExams();
        $this->sbarBlock($scene->exam->name, $scene->exam->head);
        $this->sbarHr();
        $this->sbarButton('Scene List',"/exam/" . $scene->exam->id . '/scene/','dark');
        $this->sbarButton('Next Scene',"/exam/" . $scene->exam->id . '/scene/' . $scene->id . "/next",'dark');
        $this->sbarButton('Edit Scene',"/exam/" . $scene->exam->id . '/scene/' . $scene->id . "/edit",'primary');
        $this->sbarDelete('Delete Scene',"/exam/" . $scene->exam->id . '/scene/' . $scene->id . "/destroy");
        $this->sbarButton('Create New Scene',"/exam/" . $scene->exam->id . '/scene/create','success');
        return $this->blocks;
    }

    /**
     * Called from QuestionController@show
     * @param Exam $exam
     * @return array
     */
    public function questionShow($question) {
        $this->myPracxam();
        $this->allExams();
        $this->sbarBlock($question->scene->exam->name, $question->scene->exam->head);
        $this->sbarHr();
        $exam_id = $question->scene->exam->id;
        $this->sbarButton('Scene List',"/exam/$exam_id/scene/",'dark');
        $this->sbarButton('Next Scene',"/exam/$exam_id/scene/{$question->scene_id}/next",'dark');
        $this->sbarButton('Edit Scene',"/exam/$exam_id/scene/{$question->scene_id}/edit",'primary');
        $this->sbarDelete('Delete Scene',"/exam/$exam_id/scene/{$question->scene_id}/destroy");
        $this->sbarButton('Create New Scene',"/exam/$exam_id/scene/create",'success');
        return $this->blocks;
    }

    public function sceneEdit($scene) {
        $this->myPracxam();
        $this->allExams();
        //$scene->loadMissing('exam');
        $this->sbarBlock($scene->exam->name, $scene->exam->head);
        $this->sbarHr();
        $this->sbarButton('Scene List',"/exam/" . $scene->exam->id . '/scene/','dark');
        $this->sbarButton('Next Scene',"/exam/" . $scene->exam->id . '/scene/' . $scene->id . "/next/edit",'dark');
        $this->sbarButton('Show Scene',"/exam/" . $scene->exam->id . '/scene/' . $scene->id . "/show",'primary');
        $this->sbarDelete('Delete Scene',"/exam/" . $scene->exam->id . '/scene/' . $scene->id . "/destroy");
        $this->sbarButton('Create New Scene',"/exam/" . $scene->exam->id . '/scene/create','success');
        return $this->blocks;
    }

    public function editUserExam($exam) {
        $this->sbarLink('Exams','overview',"/exam");
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
        $this->myPracxam();
        $this->allExams();
        $this->examLogo($praxexam->exam);
        $this->userSceneList($praxexam, 0);
        $this->sbarScene("View Score","/prax/{$praxexam->userexam->id}/show","dark");
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


    private function myPracxam() {
        $this->sbarLink('My Pracxam','Dashboard',"/home",'secondary');
    }

    private function allExams() {
        $this->sbarLink('All Exams','',"/exam",'secondary');
    }

    private function examLogo(Exam $exam) {
        $this->sbarBlock($exam->name, $exam->head);
    }

    private function sbarHead($head, $text) {
        $this->blocks[] = ['type' => 'sbar-head', 'head' => $head, 'text' => $text];
    }

    private function sbarHr() {
        $this->blocks[] = ['type' => 'sbar-hr'];
    }

    private function sbarBlock($head, $text) {
        $this->blocks[] = ['type' => 'sbar-block', 'head' => $head, 'text' => $text];
    }

    private function sbarLink($head, $text, $href, $color = 'dark') {
        $this->blocks[] = ['type' => 'sbar-link', 'head' => $head, 'text' => $text, 'href' => $href, 'color' => $color ];
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


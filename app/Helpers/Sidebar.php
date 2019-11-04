<?php

/**
 * todo: split class into controller-specific parts to reduce load
 */

namespace App\Helpers;

use App\Models\Exam;
use App\Models\Scene;
use App\Models\Question;
use App\Models\UserScene;
use App\Models\UserExam;
use App\Models\User as User;
use Illuminate\Support\Facades\Auth;
use App\Classes\PraxExam;

class Sidebar
{
    private $blocks = [];

    /**
     * Called from HomeController@index
     * todo: limit ?
     */
    public function sbarHomeIndex() {
        $this->allExams();
        $exams = Exam::get(['id', 'name', 'head']);
        foreach ($exams as $exam) {
            $this->sbarLink($exam->name, $exam->head, "/exam/".$exam->id."/show");
        }
        return $this->blocks;
    }

    public function sbarNoExam() {
        $this->myPracxam();
        $this->allExams();
        return $this->blocks;
    }

    //---- EXAMS ----

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

    /**
     * sbarExamEdit
     * called from ExamController@edit
     *
     * @param $exam
     * @return array
     */
    public function sbarExamEdit($exam) {
        $id = $exam->id;
        $this->sbarLink('Exams','overview',"/exam");
        $this->sbarBlock($exam->name, $exam->head);
        $this->sbarButton('Show Exam',"/exam/$id/show",'dark');
        //$this->sbarButton('Start Test',"/prax/$id/create",'dark');
        $this->sbarHead("<hr />", 'Admin Controls');
        $this->sbarButton('Cancel',"/exam/$id/show/",'dark');
        $this->sbarButton('Manage Scenes',"/exam/$id/scene/",'primary');
        $this->sbarDelete('Delete Exam',"/exam/$id/destroy");
        return $this->blocks;
    }

    //---- SCENES ----

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
        $this->sbarButton('Show Exam',"/exam/$id/show",'dark');
        //$this->sbarButton('Start Test',"/prax/$id/create",'dark');

        // todo: this should be checked before..?
        if (Auth::user()->isAdmin() || Auth::user()->isOwner($exam)) {
            $this->sbarHead("<hr />", 'Admin Controls');
            $this->sbarButton('Edit Exam', "/exam/$id/edit/", 'primary');
            $this->sbarButton('Cancel',"/exam/$id/show/",'dark');
        }
        return $this->blocks;
    }

    /**
     * Called from SceneController@show
     * @param Exam $exam
     * @return array
     */
    public function sbarSceneShow($scene) {
        $this->myPracxam();
        $this->allExams();
        $this->sbarBlock($scene->exam->name, $scene->exam->head);
        $this->sbarHr();
        $this->sbarButton('Scene List',"/exam/" . $scene->exam->id . '/scene/','dark');
        $this->sbarButton('Next Scene',"/exam/" . $scene->exam->id . '/scene/' . $scene->id . "/next",'dark');
        $this->sbarButton('Edit Scene',"/exam/" . $scene->exam->id . '/scene/' . $scene->id . "/edit",'primary');
        $this->sbarDelete('Delete Scene',"/exam/" . $scene->exam->id . '/scene/' . $scene->id . "/destroy");
        $this->sbarButton('Create New Scene',"/exam/" . $scene->exam->id . '/scene/create','primary');
        return $this->blocks;
    }

    public function sbarSceneCreate($exam) {
        $this->myPracxam();
        $this->allExams();
        $this->sbarBlock($exam->name, $exam->head);
        $this->sbarButton('Cancel',"/exam/{$exam->id}/scene",'dark');
        return $this->blocks;
    }

    /**
     * @param $scene
     * @return array
     */
    public function sbarSceneEdit($scene) {
        $id = $scene->exam->id;
        $this->myPracxam();
        $this->allExams();
        //$scene->loadMissing('exam');
        $this->sbarBlock($scene->exam->name, $scene->exam->head);
        $this->sbarButton('Show Exam',"/exam/$id/show",'dark');
        //$this->sbarButton('Start Test',"/prax/$id/create",'dark');
        $this->sbarHead("<hr />", 'Admin Controls');
        $this->sbarButton('Scene List',"/exam/$id/scene/",'dark');
        $this->sbarButton('Edit Next Scene',"/exam/$id/scene/{$scene->id}/next/edit",'primary');
        $this->sbarButton('Show Scene',"/exam/$id/scene/{$scene->id}/show",'dark');
        $this->sbarDelete('Delete Scene',"/exam/$id/scene/{$scene->id}/destroy");
        $this->sbarButton('Create New Scene',"/exam/$id/scene/create",'primary');
        return $this->blocks;
    }

    //---- QUESTIONS ----

    /**
     * todo: obolete?
     * Called from QuestionController@show
     * @param Exam $exam
     * @return array
     */
    public function sbarQuestionShow(Question $question) {
        $this->myPracxam();
        $this->allExams();
        $this->sbarBlock($question->scene->exam->name, $question->scene->exam->head);
        $this->sbarHead("<hr />", 'Admin Controls');
        $exam_id = $question->scene->exam->id;
        $this->sbarButton('Scene List',"/exam/$exam_id/scene/",'dark');
        $this->sbarButton('Next Scene',"/exam/$exam_id/scene/{$question->scene_id}/next",'dark');
        $this->sbarButton('Edit Scene',"/exam/$exam_id/scene/{$question->scene_id}/edit",'primary');
        $this->sbarDelete('Delete Scene',"/exam/$exam_id/scene/{$question->scene_id}/destroy");
        $this->sbarButton('Create New Scene',"/exam/$exam_id/scene/create",'primary');
        return $this->blocks;
    }

    public function sbarQuestionCreate(Scene $scene) {
        $this->myPracxam();
        $this->allExams();
        $this->sbarBlock($scene->exam->name, $scene->exam->head);
        $this->sbarButton('Show Scene',"/exam/{$scene->exam->id}/show",'dark');
        $this->sbarHead("<hr />", 'Admin Controls');
        $this->sbarButton('Scene List',"/exam/{$scene->exam->id}/scene/",'dark');
        $this->sbarButton('Edit Scene',"/exam/{$scene->exam->id}/scene/{$scene->id}/edit",'primary');
        $this->sbarButton('Cancel',"/exam/{$scene->exam->id}/scene/{$scene->id}/edit",'primary');
        return $this->blocks;
    }

    public function sbarQuestionEdit(Scene $scene) {
        $this->myPracxam();
        $this->allExams();
        $this->sbarBlock($scene->exam->name, $scene->exam->head);
        $this->sbarButton('Cancel',"/exam/{$scene->exam->id}/scene/{$scene->id}/edit",'primary');
        return $this->blocks;
    }

    public function sbarQuestionAnswers(Question $question) {
        $this->myPracxam();
        $this->allExams();
        $this->sbarBlock($question->scene->exam->name, $question->scene->exam->head);
        $this->sbarButton('Cancel',"/exam/{$question->scene->exam->id}/scene/{$question->scene->id}/edit",'primary');
        return $this->blocks;
    }

    //---- PRAX SIDEBARS ----

    /**
     * @param $exam
     * @return array
     */
    public function sbarPraxCreate($exam) {
        $this->sbarLink('Exams','overview',"/exam");
        $this->sbarBlock($exam->name, $exam->head);
        $this->sbarHead("Start Exam", '');
        $this->sbarButton('Cancel',"/exam/".$exam->id."/show",'dark');
        return $this->blocks;
    }

    /**
     * sbarPraxExam
     * The menu during the test, called from UserSceneController@show
     *
     * @param  PraxExam  $praxexam
     * @param  int  $order
     * @return  array
     */
    public function sbarPraxExam(PraxExam $praxexam, $order) {
        $this->myPracxam();
        $this->allExams();
        $this->examLogo($praxexam->exam);
        $this->userSceneList($praxexam, $order);
        $this->userScene("View Score","/prax/{$praxexam->userexam->id}/show","outline-dark");
        return $this->blocks;
    }

    /**
     * Called from UserExamController@show
     *
     * @param PraxExam $praxexam
     * @return array
     */
    public function sbarPraxResult(PraxExam $praxexam) {
        $this->myPracxam();
        $this->allExams();
        $this->examLogo($praxexam->exam);
        $this->userSceneList($praxexam, 0);
        $this->userScene("View Score","/prax/{$praxexam->userexam->id}/show","dark");
        return $this->blocks;
    }

    //-- private functions -- !synq with view/components/sidebar.blade.php!

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
            $this->userScene($head,$href,$col);
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

    private function userScene($head, $href, $color = 'outline-dark') {
        $this->blocks[] = ['type' => 'sbar-scene', 'head' => $head, 'href' => $href, 'color' => $color ];
    }

}


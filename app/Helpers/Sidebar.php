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
        $exams = Exam::select(['id', 'name', 'head'])
            ->whereNull('deleted_at')
            ->where(function($q) {
                if (!Auth::user()->isAdmin()) {
                    $q->where('is_public', 1)->orWhere('created_by', Auth::id());
                }
            })->orderBy('scene_count')
            ->orderBy('created_at')
            ->limit(3)
            ->get();

        $this->allExams();
        $this->sbarHead("<hr />", $exams->count() > 2 ? 'Top 3 Exams' : 'Available Exams');
        foreach ($exams as $exam) {
            $this->sbarLink($exam->name, $exam->head, "/exam/".$exam->id."/show");
        }
        $this->sbarHead("<hr />", 'Start Your Own');
        $this->sbarButton('Create New Exam',"/exam/create",'primary');
        return $this->blocks;
    }

    public function sbarNoExam() {
        //$this->myPracxam();
        $this->allExams();
        return $this->blocks;
    }

    //---- EXAMS ----

    /**
     * Looking at an overview of public exams.
     * What buttons do you need?
     *
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
        $this->sbarButton('Start Test',"/prax/$id/create",'primary');
        if ($exam->canEdit(Auth::user())) {
            $this->sbarHead("<hr />", 'Exam Editor');
            $this->sbarButton('Edit Exam',"/exam/$id/edit/",'primary');
            //$this->sbarDelete('Delete Exam',"/exam/$id/destroy");
            $this->sbarButton('Manage Scenes',"/exam/$id/scene/",'primary');
            $this->sbarButton('Add New Scene',"/exam/$id/scene/create",'primary');
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
    public function sbarExamCreate() {
        $this->myPracxam();
        $this->allExams();
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
        $this->myPracxam();
        $this->allExams();
        $this->examLogo($exam);
        $this->sbarButton('Show Exam',"/exam/$id/show",'dark');
        //$this->sbarButton('Start Test',"/prax/$id/create",'dark');
        $this->sbarHead("<hr />", 'Exam Editor');
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

        // todo: this is checked before..?
        if (Auth::user()->isAdmin() || Auth::user()->isOwner($exam)) {
            $this->sbarHead("<hr />", 'Exam Editor');
            $this->sbarButton('Edit Exam', "/exam/$id/edit/", 'primary');
            $this->sbarButton('Create New Scene',"/exam/" . $exam->id . '/scene/create','primary');
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
        $this->sbarButton('Show Exam',"/exam/$scene->exam_id/show",'dark');
        $this->sbarButton('Show Next Scene', "/exam/" . $scene->exam->id . '/scene/' . $scene->id . "/next", 'dark');
        if (Auth::user()->isAdmin() || Auth::user()->isOwner($scene->exam)) {
            $this->sbarHead("<hr />", 'Exam Editor');
            $this->sbarButton('Scene List', "/exam/" . $scene->exam->id . '/scene/', 'dark');
            $this->sbarButton('Edit This Scene', "/exam/" . $scene->exam->id . '/scene/' . $scene->id . "/edit", 'primary');
            $this->sbarButton('Create New Scene', "/exam/" . $scene->exam->id . '/scene/create', 'primary');
        }
        return $this->blocks;
    }

    public function sbarSceneCreate($exam) {
        $this->myPracxam();
        $this->allExams();
        $this->sbarBlock($exam->name, $exam->head);
        $this->sbarButton('Show Exam',"/exam/$exam->id/show",'dark');
        if (Auth::user()->isAdmin() || Auth::user()->isOwner($exam)) { // todo
            $this->sbarHead("<hr />", 'Exam Editor');
            $this->sbarButton('Scene List',"/exam/" . $exam->id . '/scene/','dark');
            $this->sbarButton('Edit Exam', "/exam/{$exam->id}/edit/", 'primary');
        }
        return $this->blocks;
    }

    /**
     * @param $scene
     * @return array
     */
    public function sbarSceneEdit($scene) {
        $exam_id = $scene->exam->id;
        $this->myPracxam();
        $this->allExams();
        //$scene->loadMissing('exam');
        $this->sbarBlock($scene->exam->name, $scene->exam->head);
        $this->sbarButton('Show Exam',"/exam/$exam_id/show",'dark');
        //$this->sbarButton('Start Test',"/prax/$id/create",'dark');
        $this->sbarHead("<hr />", 'Exam Editor');
        $this->sbarButton('Edit Exam', "/exam/$exam_id/edit/", 'primary');
        $this->sbarButton('Scene List',"/exam/$exam_id/scene/",'dark');
        $this->sbarButton('Edit Next Scene',"/exam/$exam_id/scene/{$scene->id}/next/edit",'primary');
        $this->sbarButton('Show Scene',"/exam/$exam_id/scene/{$scene->id}/show",'dark');
        $this->sbarDelete('Delete Scene',"/exam/$exam_id/scene/{$scene->id}/destroy");
        $this->sbarButton('Create New Scene',"/exam/$exam_id/scene/create",'primary');
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
        $scene = $question->scene;
        $exam_id = $question->scene->exam->id;
        $this->myPracxam();
        $this->allExams();
        $this->sbarBlock($question->scene->exam->name, $question->scene->exam->head);
        $this->sbarButton('Show Exam',"/exam/$exam_id/show",'dark');
        $this->sbarHead("<hr />", 'Exam Editor');
        $this->sbarButton('Edit Exam', "/exam/$exam_id/edit/", 'primary');
        $this->sbarButton('Scene List',"/exam/$exam_id/scene/",'dark');
        $this->sbarButton('Edit This Scene',"/exam/$exam_id/scene/{$question->scene_id}/edit",'primary');
        $this->sbarButton('Edit Next Scene',"/exam/$exam_id/scene/{$question->scene_id}/next/edit",'primary');
        //$this->sbarDelete('Delete Scene',"/exam/$exam_id/scene/{$question->scene_id}/destroy");
        $this->sbarButton('Create New Scene',"/exam/$exam_id/scene/create",'primary');
        return $this->blocks;
    }

    public function sbarQuestionCreate(Scene $scene) {
        $exam_id = $scene->exam->id;
        $this->myPracxam();
        $this->allExams();
        $this->sbarBlock($scene->exam->name, $scene->exam->head);
        $this->sbarButton('Show Exam',"/exam/$exam_id/show",'dark');
        $this->sbarButton('Show Scene',"/exam/$exam_id/scene/{$scene->id}/show",'dark');
        $this->sbarHead("<hr />", 'Exam Editor');
        $this->sbarButton('Edit Exam', "/exam/$exam_id/edit/", 'primary');
        $this->sbarButton('Scene List',"/exam/$exam_id/scene/",'dark');
        $this->sbarButton('Edit This Scene',"/exam/$exam_id/scene/{$scene->id}/edit",'primary');
        return $this->blocks;
    }

    public function sbarQuestionEdit(Question $question ) {
        $scene = $question->scene;
        $exam_id = $scene->exam->id;
        $this->myPracxam();
        $this->allExams();
        $this->sbarBlock($scene->exam->name, $scene->exam->head);
        $this->sbarButton('Show Exam',"/exam/$exam_id/show",'dark');
        $this->sbarButton('Show Scene',"/exam/$exam_id/scene/{$scene->id}/show",'dark');
        $this->sbarHead("<hr />", 'Exam Editor');
        $this->sbarButton('Edit Exam', "/exam/$exam_id/edit/", 'primary');
        $this->sbarButton('Scene List',"/exam/$exam_id/scene/",'dark');
        $this->sbarButton('Edit Scene',"/exam/$exam_id/scene/{$scene->id}/edit",'primary');
        $this->sbarButton('Manage Answers',"/exam/$exam_id/scene/{$scene->id}/question/{$question->id}/answers",'primary');
        if ($scene->scene_type_id === 2) {
            $this->sbarButton('Edit Next Question',"/exam/$exam_id/scene/{$scene->id}/question/{$question->id}/next/edit",'primary');
            $this->sbarButton('Create New Question', "/exam/$exam_id/scene/{$scene->id}/question/create", 'primary');
        }
        $this->sbarDelete('Delete This Question',"/exam/$exam_id/scene/{$scene->id}/question//destroy", 'Are you sure you want to delete this question?');
        return $this->blocks;
    }

    public function sbarQuestionAnswers(Question $question) {
        $exam_id = $question->scene->exam->id;
        $this->myPracxam();
        $this->allExams();
        $this->sbarBlock($question->scene->exam->name, $question->scene->exam->head);
        $this->sbarButton('Show Exam',"/exam/$exam_id/show",'dark');
        $this->sbarButton('Show Scene',"/exam/$exam_id/scene/{$question->scene->id}/show",'dark');
        $this->sbarHead("<hr />", 'Exam Editor');
        $this->sbarButton('Edit Exam', "/exam/$exam_id/edit/", 'primary');
        $this->sbarButton('Scene List',"/exam/$exam_id/scene/",'dark');
        $this->sbarButton('Edit Scene',"/exam/$exam_id/scene/{$question->scene->id}/edit",'primary');
        $this->sbarButton('Edit Question',"/exam/$exam_id/scene/{$question->scene->id}/question/{$question->id}/edit",'primary');
        if ($question->scene->scene_type_id === 2) {
            $this->sbarButton('Edit Next Question',"/exam/$exam_id/scene/{$question->scene->id}/question/{$question->id}/next/edit",'primary');
            $this->sbarButton('Create New Question', "/exam/$exam_id/scene/{$question->scene->id}/question/create", 'primary');
        }
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
        if (Auth::user()->isAdmin() || Auth::user()->isOwner($exam)) {
            $this->sbarHead("<hr />", 'Exam Editor');
            $this->sbarButton('Edit Exam', "/exam/$exam->id/edit/", 'primary');
        }
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
        if (Auth::user()->isAdmin() || Auth::user()->isOwner($praxexam->exam)) {
            $this->sbarHead("<hr />", 'Exam Editor');
            $this->sbarButton('Edit Exam', "/exam/{$praxexam->exam->id}/edit/", 'primary');
            $userscene = $praxexam->userSceneByOrder($order);
            $this->sbarButton('Edit Scene', "/exam/{$praxexam->exam->id}/scene/{$userscene->scene_id}/edit", 'primary');
        }
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
        // No Exam Editor
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


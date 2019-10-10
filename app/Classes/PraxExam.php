<?php

namespace App\Classes;

use App\Models\Exam;
use App\Models\Scene;
use App\Models\UserExam;
use App\Models\UserScene;
use App\Classes\PraxQuestion;
use App\Classes\PraxAnswer;
use DB;

class PraxExam
{
    public $exam = NULL;
    public $userexam = NULL;
    public $praxscenes;

    /**
     * Load all the data we need to make a complete PraxExam piramide.
     *
     * @param int $userexam_id
     * @return $this
     */
    public function loadUserExamData($userexam_id) {
        //DB::enableQueryLog();
        $userexam = UserExam::where('id','=',$userexam_id)
            ->with('exam','userscenes','userscenes.userquestions','userscenes.userquestions.useranswers')
            ->with('userscenes.scene','userscenes.userquestions.question','userscenes.userquestions.question.answers')
            ->firstOrFail();
        //dd(DB::getQueryLog(),$userexam);
        $this->setUserExamData($userexam);
        return $this;
    }

    /**
     * @param UserExam $userexam
     * @return $this
     */
    public function setUserExamData(UserExam $userexam) {
        $this->userexam = $userexam;
        $this->exam = $userexam->exam;
        $this->praxscenes = collect();
        foreach($userexam->userscenes as $userscene) {
            $this->praxscenes->add((new PraxScene())->setUserSceneData($userscene, $this));
        }
        //dd($this);
        return $this;
    }

    
}
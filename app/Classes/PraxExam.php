<?php

namespace App\Classes;

use App\Models\Exam;
use App\Models\Scene;
use App\Models\UserExam;
use App\Models\UserScene;
use App\Classes\PraxQuestion;
use App\Classes\PraxAnswer;

class PraxExam
{
    public $exam;
    public $userexam = null;
    public $praxscenes = [];

    public function __construct(Exam $exam) {
        $this->exam = $exam;
        foreach($exam->scenes as $scene) {
            $this->praxscenes[] = new PraxScene($scene);
        }
    }

    /**
     * @param UserScene $userscene
     */
    public function setUserExam(UserExam $userexam) {
        $this->userexam = $userexam;
        foreach($this->praxscenes as $praxscene) {
            foreach($userexam->userscenes as $userscene) {
                if ($praxscene->scene->id === $userscene->scene_id) {
                    $praxscene->setUserScene($userscene);
                    break;
                }
            }
        }
        //dd($this);
        return $this;
    }


    
}
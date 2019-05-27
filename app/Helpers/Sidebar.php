<?php 

namespace App\Helpers;

use App\Models\Exam;
use App\Models\Scene;
use App\Models\UserScene;
use App\Models\User as User;
use Illuminate\Support\Facades\Auth;

class Sidebar
{
    private $blocks = [];

    public function examOverview($exam = null) {
        if (!empty($exam)) {
            $id = $exam->id;
            $this->sbarBlock($exam->name, $exam->head);
            $this->sbarButton('Back to Overview',"/exam",'dark');
            $this->sbarButton('Start Practice Exam',"/prax/$id/create",'dark');
            //- todo: isAdmin($id) to test if it's the owner OR admin
            $user = Auth::user();
            if (!empty($user) && $user->isAdmin()) {
                // todo: don't use id's in text
                $this->sbarHead("Exam", 'Admin Controls');
                $this->sbarButton('Edit Exam',"/exam/$id/edit/",'dark');
                $this->sbarButton('Delete Exam',"/exam/$id/kill/",'danger');
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
        // user was tested for admin
        $this->sbarLink('Exams','overview',"/exam");
        $this->sbarBlock($exam->name, $exam->head);
        $id = $exam->id;
        $this->sbarHead("Edit Exam $id", 'Admin Controls');
        $this->sbarButton('Cancel',"/exam/$id/show/",'dark');
        return $this->blocks;
    }

    public function sceneExams($scene) {
        $exams = $scene->exams()->get();
        foreach ($exams as $exam) {
            $this->sbarLink($exam->name,$exam->head,"/exam/".$exam->id."/show");
        }
        $this->sbarHead('Scene ' . $scene->id, 'Admin Controls');
        
        $next = $scene->nextSceneId($scene->id);
        if (!empty($next)) {
            $this->sbarButton('Next Scene',"/scene/$next/show",'dark');
        }

        $this->sbarButton('Edit Scene','/scene/'.$scene->id."/edit",'dark');
        $this->sbarButton('Delete Scene','/scene/'.$scene->id."/kill",'danger');
        return $this->blocks;
    }

    public function editUserExam($exam) {
        $this->sbarBlock($exam->name, $exam->head);
        $this->sbarHead("Start Exam", '');
        $this->sbarButton('Cancel',"/exam/".$exam->id."/show",'dark');
        return $this->blocks;
    }


    public function practiceExam($prax_id, $order) {
        // load all userscenes and their userquestions objects
        $userscenes = UserScene::where('userexam_id','=',$prax_id)->orderBy('order')->with('userquestions')->get();
        //dd($userscenes);
        foreach($userscenes as $us) {
            $scorder = $us->order;
            $head = "Scene $scorder";
            $href = "/prax/$prax_id/scene/$scorder";
            //- show question result, lock answered questions.
            $locked = true;
            foreach ($us->userquestions as $uq) {
                if (is_null($uq->result)) {
                    $locked = false;
                }
            }
            $col = $locked ? "secondary" : "dark";
            if ($order != $scorder) {
                $col = 'outline-' . $col;
            }
            $this->sbarScene($head,$href,$col);
        }
        $this->sbarScene("View Score","/examu/$prax_id/result","outline-dark");
        return $this->blocks;
    }

    //-- private functions -- synq with view/components/sidebar.blade.php

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

    private function sbarScene($head, $href, $color = 'outline-dark') {
        $this->blocks[] = ['type' => 'sbar-scene', 'head' => $head, 'href' => $href, 'color' => $color ];
    }

}


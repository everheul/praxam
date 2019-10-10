<?php

namespace App\Classes;

use App\Models\Scene;
use App\Models\UserScene;
use App\Classes\PraxExam;
use App\Classes\PraxQuestion;
use App\Classes\PraxAnswer;

class PraxScene
{
    public $parent = NULL;      //-> PraxExam
    public $userscene = null;   //-> UserScene
    public $scene = null;       //-> Scene
    public $praxquestions;      //-= PraxQuestion Collection

    /**
     * Load all the data we need to make a complete PraxScene piramide.
     *
     * @param int $userscene_id
     * @return $this
     */
    public function loadUserSceneData(int $userscene_id) {
        //DB::enableQueryLog();
        $userscene = UserScene::where('id', '=', $userscene_id)
            ->with('scene','userquestions','userquestions.useranswers')
            ->with('userquestions.question','userquestions.question.answers')
            ->firstOrFail();
        //dd(DB::getQueryLog());
        $this->setUserSceneData($userscene);
        return $this;
    }

    /**
     * Load all the data we need to make a complete PraxScene piramide.
     *
     * @param int $userscene_id
     * @return $this
     */
    public function loadUserSceneDataByOrder(int $userexam_id, int $order) {
        //DB::enableQueryLog();
        $userscene = UserScene::where('userexam_id', $userexam_id)
            ->where('order', $order)
            ->with('scene','userquestions','userquestions.useranswers')
            ->with('userquestions.question','userquestions.question.answers')
            ->firstOrFail();
        //dd(DB::getQueryLog());
        $this->setUserSceneData($userscene);
        return $this;
    }

    /**
     * Create the rest of the PraxScene piramide.
     *
     * @param UserScene $userscene
     * @param UserExam|null $parent
     * @return $this
     */
    public function setUserSceneData(UserScene $userscene, PraxExam $parent = NULL) {
        $this->parent = $parent;
        $this->userscene = $userscene;
        $this->scene = $userscene->scene;
        $this->praxquestions = collect();
        //- create the PraxQuestions:
        foreach($userscene->userquestions as $userquestion) {
            $this->praxquestions->add((new PraxQuestion())->setUserQuestionData($userquestion, $this));
        }
        return $this;
    }

    /**
     * @param int $index
     * @return \App\Classes\PraxAnswer
     */
    public function praxquestion($index = 0) {
        if (isset($this->praxquestions[$index])) {
            return $this->praxquestions[$index];
        } else {
            dd("userquestion missing??",$this);
        }
    }

    /**
     * JSON list with question data for template <scripts> sections, to be used by the accordion.
     */
    public function questionTypes(): string {
        $a = [];
        $q = $this->praxquestions->pluck('question')->all();
        foreach($q as $key => $question) {
            $a[] = Array('id' => $question->id,
                        'type' => $question->question_type_id,
                        'first' => ($key === array_key_first($q)),
                        'last' => ($key === array_key_last($q)));
        }
        return json_encode($a);
    }

    public function nextQuestion() {
        foreach ($this->praxquestions as $key => $praxquestion) {
            if (!$praxquestion->locked) {
                return $key;
            }
        }
        return 0;
    }

    /**
     * @param $image
     * @return string
     */
    public function getImageSizeStr()
    {
        $image = $this->scene->image;
        if (!empty($image)) {
            $size = getimagesize(public_path() . '/img/' . $image);
            if (!empty($size)) {
                $width = ($size[0] > 0) ? (($size[0] < 9999) ? $size[0] : 0) : 0;
                $height = ($size[1] > 0) ? (($size[1] < 9999) ? $size[1] : 0) : 0;
                if ($width && $height) {
                    return " width=\"$width\" height=\"$height\"";
                }
            }
        }
        return "";
    }

    public function maxScore() {
        $total = 0;
        foreach($this->praxquestions as $praxquestion) {
            $total += $praxquestion->question->points;
        }
        return $total;
    }

    public function score() {
        $total = 0;
        foreach($this->praxquestions as $praxquestion) {
            if (!is_null($praxquestion->userquestion) && !is_null($praxquestion->userquestion->result)) {
                $total += $praxquestion->userquestion->result;
            }
        }
        return $total;
    }
}
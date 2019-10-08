<?php

namespace App\Classes;

use App\Models\Scene;
use App\Models\UserScene;
use App\Classes\PraxQuestion;
use App\Classes\PraxAnswer;

class PraxScene
{
    public $scene;
    public $userscene = null;
    public $praxquestions = [];

    public function __construct(Scene $scene) {
        $this->scene = $scene;
        foreach($scene->questions as $question) {
            $this->praxquestions[] = new PraxQuestion($question);
        }
    }

    /**
     * @param UserScene $userscene
     */
    public function setUserScene(UserScene $userscene) {
        $this->userscene = $userscene;
        foreach($this->praxquestions as $praxquestion) {
            foreach($userscene->userquestions as $userquestion) {
                if ($praxquestion->question->id === $userquestion->question_id) {
                    $praxquestion->setUserQuestion($userquestion);
                    break;
                }
            }
        }
        //dd($this);
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
            //- userquestion missing??
            dd($this);
        }
    }

    /**
     * JSON list with question data for template <scripts> sections, to be used by the accordion.
     * Note: the Scene must be loaded with() questions.
     */
    public function questionTypes(): string {
        $a = [];
        $q = $this->scene->questions->all();
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
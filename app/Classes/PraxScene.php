<?php

namespace App\Classes;

use App\Models\Scene;
use App\Models\UserScene;
use App\Classes\PraxQuestion;
use App\Classes\PraxAnswer;

class PraxScene
{
    public $scene;
    public $userscene;
    public $praxquestions = [];

    public function __construct(Scene $scene, UserScene $userscene) {
        $this->$scene = $scene;
        $this->$userscene = $userscene;
        foreach($userscene->userquestions as $userquestion) {
            $question = $scene->questions->find($userquestion->question_id);
            $this->praxquestions[] = new PraxQuestion($question, $userquestion);
        }
    }

    /**
     * JSON list with question data for template <scripts> sections, to be used by the accordion.
     * Note: the Scene must be loaded with questions.
     */
    public function questionTypes(): string {
        $a = [];
        $q = $this->scene->questions;
        foreach($q as $key => $question) {
            $a[] = Array('id' => $question->id,
                'type' => $question->question_type_id,
                'first' => ($key === array_key_first($q)),
                'last' => ($key === array_key_last($q)));
        }
        return json_encode($a);
    }


}
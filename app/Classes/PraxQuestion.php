<?php

namespace App\Classes;

use App\Models\Question;
use App\Models\UserQuestion;
use App\Classes\PraxAnswer;

class PraxQuestion
{
    public $question;
    public $userquestion;
    public $praxanswers = [];
    public $locked = false;

    public function __construct(Question $question, UserQuestion $userquestion) {

        $this->question = $question;
        $this->userquestion = $userquestion;

        if (!is_null($userquestion->result)) {
            $this->locked = true;
        }

        foreach($question->answers as $answer) {
            $useranswer = $userquestion->useranswers->firstWhere('question_id',$answer->id); // = null for not selected answers
            $this->praxanswers[] = new PraxAnswer($answer, $useranswer, $this->locked);
        }
    }

}

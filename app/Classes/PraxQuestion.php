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

        foreach($question->answers->sortBy('order') as $answer) {
            $this->praxanswers[] = new PraxAnswer($answer, $this->locked);
        }
        
        //- add the selected answers in a double loop :-( todo?
        foreach($userquestion->useranswers as $useranswer) {
            foreach($this->praxanswers as $praxanswer) {
                if ($praxanswer->answer->id === $useranswer->answer_id) {
                    $praxanswer->setUserAnswer($useranswer);
                }
            }
        }            
        //    $useranswer = $userquestion->useranswers->firstWhere('question_id', $answer->id); // = null for not selected answers

        //dd($this);
    }

    /**
     * Return the string that disables the 'Done' button if locked
     * @return string
     */
    public function disabled() {
        return ($this->locked) ? " disabled" : "";
    }

}

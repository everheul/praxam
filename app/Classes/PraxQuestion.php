<?php

namespace App\Classes;

use App\Models\Question;
use App\Models\UserQuestion;
use App\Classes\PraxAnswer;

class PraxQuestion
{
    public $question;
    public $userquestion = null;
    public $praxanswers = [];
    public $locked = false;

    public function __construct(Question $question) {
        $this->question = $question;
        foreach($question->answers->sortBy('order') as $answer) {
            $this->praxanswers[] = new PraxAnswer($answer);
        }
    }

    /**
     * @param UserQuestion $userquestion
     */
    public function setUserQuestion(UserQuestion $userquestion) {
        $this->userquestion = $userquestion;
        if (!is_null($userquestion->result)) {
            $this->locked = true;
        }
        //- add the selected answers in a double loop :-( todo?
        foreach($userquestion->useranswers as $useranswer) {
            foreach($this->praxanswers as $praxanswer) {
                if ($praxanswer->answer->id === $useranswer->answer_id) {
                    $praxanswer->setUserAnswer($useranswer);
                    break;
                }
            }
            // todo: answer not found??
        }
        return $this;
    }

    /**
     * This FormId is only important when there is a userquestion, otherwise it just has to be unique.
     *
     * @return mixed
     */
    public function getFormId() {
        return (empty($this->userquestion)) ? $this->question->id : $this->userquestion->id;
    }

    /**
     * Returns the string that disables the 'Done' button if locked
     * @return string
     */
    public function disabledStr() {
        return ($this->locked) ? " disabled" : "";
    }

    /**
     * used for scene type 2 questions
     *
     * @return mixed
     */
    public function checkedTabStr() {
        return ($this->locked) ? "&nbsp;&nbsp;<i class=\"fa fa-check-circle\" aria-hidden=\"true\"></i>" : ""; //   "<i class=\"fas fa-check\"></i>"" &#61452; "
    }
}

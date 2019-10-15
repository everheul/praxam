<?php

namespace App\Classes;

use App\Models\Answer;
use App\Models\UserAnswer;

class PraxAnswer
{
    public $parent = NULL;          //-> PraxQuestion
    public $answer = NULL;
    public $useranswer = NULL;
    public $checked = false;
    public $locked = false;


    public function setAnswerData(Answer $answer, PraxQuestion $parent = NULL) {
        $this->answer = $answer;
        $this->parent = $parent;
        return $this;
    }

    public function setUserAnswerData(UserAnswer $useranswer) {
        $this->useranswer = $useranswer;
        $this->checked = true;
        $this->locked = $this->parent->locked;
        return $this;
    }
    
    /**
     * Return the string that selects the inputboxes/radioboxes if checked
     * @return  string
     */
    public function checkedStr() {
        return ($this->checked) ? ' checked=1' : "";
    }

    /**
     * Return the string that holds the correct order of this answer (type 3) if locked
     * @return  string
     */
    public function orderStr() {
        return ($this->locked && $this->answer->correct_order > 0) ? $this->answer->correct_order . '. ' : "";
    }

    /**
     * Returns the css class to color the answer correct or wrong.
     * A correct answer does not have to be in the correct order here.
     *
     * @return string
     */
    public function coolnessStr() {
        if ($this->locked) {
            $cool = is_null($this->answer->correct_order) ? ($this->answer->is_correct === 1) : ($this->answer->correct_order > 0);
            return $cool ? 'correct ' : 'wrong ';
        }
        return '';
    }

}
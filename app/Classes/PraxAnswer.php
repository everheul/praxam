<?php

namespace App\Classes;

use App\Models\Answer;
use App\Models\UserAnswer;

class PraxAnswer
{
    public $answer;
    public $useranswer;
    public $locked = false;
    public $checked = false;

    public function __construct(Answer $answer, $locked = false) {

        $this->answer = $answer;
        $this->locked = $locked;
    }

    public function setUserAnswer(UserAnswer $useranswer) {
        $this->useranswer = $useranswer;
        $this->checked = true;
    //    $this->correct = ;
    }
    
    /**
     * Return the string that disables the inputboxes/radioboxes if locked
     * @return string
     */
    public function disabled() {
        return ($this->locked) ? ' disabled=1' : "";
    }

    /**
     * Return the string that selects the inputboxes/radioboxes if checked
     * @return  string
     */
    public function checked() {
        return ($this->checked) ? ' checked=1' : "";
    }

    /**
     * Return the string that holds the correct order of answer if locked
     * @return  string
     */
    public function order() {
        return ($this->locked && $this->answer->correct_order > 0) ? $this->answer->correct_order . '. ' : "";
    }

    /**
     * Returns the css class to color the answer correct or wrong.
     * A correct answer does not have to be in the correct order here.
     *
     * @return string
     */
    public function coolness() {
        if ($this->locked) {
            $cool = is_null($this->answer->correct_order) ? ($this->answer->is_correct === 1) : ($this->answer->correct_order > 0);
            return $cool ? 'correct ' : 'wrong ';
        }
        return '';
    }

}
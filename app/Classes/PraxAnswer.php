<?php

namespace App\Classes;

use App\Models\Answer;
use App\Models\UserAnswer;

class PraxAnswer
{
    public $answer;
    public $useranswer;
    private $locked = false;
    private $checked = false;

    public function __construct(Answer $answer, UserAnswer $useranswer, $locked) {

        $this->answer = $answer;
        $this->useranswer = $useranswer;
        $this->locked = $locked;

        if (!empty($useranswer)) {
            $this->checked = true;
        }
    }

    /**
     * Return the string that disables the inputboxes/radioboxes if locked
     * @return string
     */
    public function disabled() {
        return ($this->locked) ? "disabled" : "";
    }

    /**
     * Return the string that selects the inputboxes/radioboxes if checked
     * @return  string
     */
    public function checked() {
        return ($this->checked) ? "checked=\"checked\"" : "";
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
            return $cool ? 'correct' : 'wrong';
        }
        return '';
    }

}
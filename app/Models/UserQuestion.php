<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserQuestion extends Model
{
    public $timestamps = false; //- same as userscenes, made with userexam.

    protected $table = 'userquestions';

    //- fields that may be filled by create() and update();
    //  all others will be ignored without warning (!)
    protected $fillable = [ 'userscene_id', 'question_id', 'order'];

    /**
     * The relation with userscenes (OneToMany Inverse)
     */
    public function userscene() {
        return $this->belongsTo('App\Models\UserScene', 'userscene_id', 'id');
    }

    /**
     * The relation with questions (OneToMany Inverse)
     */
    public function question() {
        return $this->belongsTo('App\Models\Question', 'question_id', 'id');
    }

    /**
     * The relation with useranswers (OneToMany)
     */
    public function useranswers() {
        return $this->hasMany('App\Models\UserAnswer', 'userquestion_id', 'id');
    }

    /**
     * Store the Question points (if the answer was correct) or 0 in UserQuestion.
     * Called from UserQuestionController with the UserQuestion's Question and its Answers loaded.
     * 
     * After this, this UserQuestion will be locked for ever.
     *
     * @param Question $question
     * @param array $checkedAnswerIds
     * @return bool
     */
    public function calcResult(Array $checkedAnswerIds) {

        //- make sure there is data
        $this->loadMissing('question','question.answers');

        //- call the right result function
        $type = $this->question->question_type_id;
        $calcFunc = "calcResultType$type";
        $result = $this->{$calcFunc}($checkedAnswerIds);

        //- store the result, lock the question
        $this->result = $result;
        $this->update();
        return $result;
    }

    /**
     * Only one answer is correct & only one is checked, make sure its the right one.
     *
     * @param array $checkedAnswerIds
     * @return int
     */
    private function calcResultType1(array $checkedAnswerIds) {
        if (count($checkedAnswerIds) === 1) {
            foreach($this->question->answers as $answer) {
                if ($answer->id == $checkedAnswerIds[0]) {
                    if ($answer->is_correct) {
                        return $this->question->points;
                    }
                }
            }
        }
        return 0;
    }

    /**
     * More answers are correct, make sure all those, and only those, were checked.
     *
     * @param array $checkedAnswerIds
     * @return int
     */
    private function calcResultType2(array $checkedAnswerIds) {
        foreach($this->question->answers as $answer) {
            if (in_array($answer->id, $checkedAnswerIds)) {
                if (!($answer->is_correct)) {
                    //- wrong answer checked
                    return 0;
                }
            } else {
                if ($answer->is_correct) {
                    //- correct answer missed
                    return 0;
                }
            }
        }
        return $this->question->points;
    }

    /**
     * More answers are correct and they have to be in the correct order.
     *
     * @param array $checkedAnswerIds
     * @return int
     */
    private function calcResultType3(array $checkedAnswerIds) {
        foreach($this->question->answers as $answer) {
            if ($answer->is_correct) {
                $pos = $answer->correct_order - 1;
                if (!((isset($checkedAnswerIds[$pos])) && ($checkedAnswerIds[$pos] == $answer->id))) {
                    //- it should be there..
                    return 0;
                }
            } else {
                if (in_array($answer->id, $checkedAnswerIds)) {
                    //- wrong answer selected, order doesn't matter
                    return 0;
                }
            }
        }
        return $this->question->points;
    }


}

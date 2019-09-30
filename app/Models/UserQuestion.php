<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserQuestion extends Model
{
    public $timestamps = false; //- same as userscenes, made with userexam.

    public $question = null;
    
    protected $table = 'userquestions';

    //- fields that may be filled by create() and update();
    //  all others will be ignored without warning (!)
    protected $fillable = [ 'userscene_id', 'question_id'];

    /**
     * The relation with userscenes (OneToMany Inverse)
     */
    public function userscene() {
        return $this->belongsTo('App\Models\UserScene', 'id', 'userscene_id');
    }

    /**
     * The relation with questions (OneToMany Inverse)
     */
    public function question() {
        return $this->belongsTo('App\Models\Questions', 'id', 'question_id');
    }

    /**
     * The relation with useranswers (OneToMany)
     */
    public function useranswers() {
        return $this->hasMany('App\Models\UserAnswer', 'userquestion_id', 'id');
    }

    /**
     * Store the Question points (if the answer was correct) or 0 in UserQuestion.
     * Called from UserQuestionController with the Question and its Answers loaded.
     * 
     * After this, this UserQuestion will be locked for ever.
     *
     * @param Question $question
     * @param array $userAnswerIds
     */
    public function calcResult(Question $question, Array $userAnswerIds) {

        switch ($question->question_type_id) {
            case 1:
                $result = $this->calcResultType1($question,$userAnswerIds);
                break;
            case 2:
                $result = $this->calcResultType2($question,$userAnswerIds);
                break;
            case 3:
                $result = $this->calcResultType3($question,$userAnswerIds);
                break;
        }
        $this->result = $result;
        $this->update();
    }

    /**
     * Only one answer is correct and checked, make sure its the right one.
     *
     * @param $question
     * @param $userAnswerIds
     * @return int
     */
    private function calcResultType1($question,$userAnswerIds) {
        foreach($question->answers as $answer) {
            if ($answer->id == $userAnswerIds[0]) {
                if ($answer->is_correct) {
                    return $question->points;
                }
            }
        }
        return 0;
    }

    /**
     * More answers are correct, make sure all those, and only those, were checked.
     *
     * @param $question
     * @param $userAnswerIds
     * @return int
     */
    private function calcResultType2($question,$userAnswerIds) {
        foreach($question->answers as $answer) {
            if (in_array($answer->id,$userAnswerIds)) {
                if (!($answer->is_correct)) {
                    return 0;
                }
            } else {
                if ($answer->is_correct) {
                    return 0;
                }
            }
        }
        return $question->points;
    }

    /**
     * More answers are correct and they have to be in the correct order.
     *
     * @param $question
     * @param $userAnswerIds
     * @return int
     */
    private function calcResultType3($question,$userAnswerIds) {
        foreach($question->answers as $answer) {
            if ($answer->is_correct) {
                $pos = $answer->order - 1;
                if (!((isset($userAnswerIds[$pos])) && ($userAnswerIds[$pos] == $answer->id))) {
                    return 0;
                }
            } else {
                if (in_array($answer->id, $userAnswerIds)) {
                    return 0;
                }
            }
        }
        return $question->points;
    }


}

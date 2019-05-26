<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserQuestion extends Model
{
    protected $table = 'userquestions';

    /**
     * The relation with userscenes (OneToMany Inverse)
     */
    public function userscenes() {
        return $this->belongsTo('App\Models\UserScene', 'id', 'userscene_id');
    }

    /**
     * The relation with questions (OneToMany Inverse)
     */
    public function questions() {
        return $this->belongsTo('App\Models\Questions', 'id', 'question_id');
    }

    /**
     * The relation with useranswers (OneToMany)
     */
    public function useranswers() {
        return $this->hasMany('App\Models\UserAnswer', 'userquestion_id', 'id');
    }

    public function create($usid, $qid) {
        $this->userscene_id = $usid;
        $this->question_id = $qid;
        $this->save();
        return $this;
    }

    /**
     * Store the questions points (if the answer was correct) or 0 in this UserQuestion.
     * Called from AjaxController with the Question and its Answers loaded.
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
        $this->save();
    }

    private function calcResultType1($question,$userAnswerIds) {
        foreach($question->answers as $answer) {
            if ($answer->is_correct) {
                if ($answer->id != $userAnswerIds[0]) {
                    return 0;
                }
            }
        }
        return $question->points;
    }

    private function calcResultType2($question,$userAnswerIds) {
        foreach($question->answers as $answer) {
            if ($answer->is_correct) {
                foreach($userAnswerIds as $uaid) {
                    if ($answer->id == $uaid) {
                        continue;
                    }
                }
                return 0;
            }
        }
        return $question->points;
    }

    private function calcResultType3($question,$userAnswerIds) {
        foreach($question->answers as $answer) {
            if ($answer->is_correct) {
                foreach($userAnswerIds as $order => $uaid) {
                    if (($answer->id == $uaid) && ($answer->order == $order + 1)) {
                        continue;
                    }
                }
                return 0;
            }
        }
        return $question->points;
    }


}

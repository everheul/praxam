<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Collection as Collection;
use App\Models\Answer;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Question
 *
 *  question_type_id:
 *  1 - One correct answer only (radioboxes)
 *  2 - One or more correct answers (checkboxes)
 *  3 - Two or more correct answers in the correct order (two sortables)
 *  4 - ?
 *
 */
class Question extends Model
{
    use SoftDeletes;

    /**
    //- fields that may be filled by create() and update();
    //  all others will be ignored without warning (!)
     *
     * @var array
     */
    protected $fillable = [ 'scene_id', 'question_type_id', 'order', 'head', 'text', 'explanation', 'points', 'answer_count', 'is_valid'];

    /**
     * The relation with scenes (OneToMany Inverse)
     */
    public function scene() {
        return $this->belongsTo('App\Models\Scene');
    }

    /**
     * The relation with question_types (OneToMany Inverse)
     */
    public function questionType() {
        return $this->belongsTo('App\Models\QuestionType');
    }

    /**
     * The relation with userquestions (OneToMany)
     */
    public function userquestions() {
        return $this->hasMany('App\Models\UserQuestion', 'question_id', 'id');
    }

    /**
     * The relation with answers (OneToMany Inverse)
     */
    public function answers() {
        return $this->hasMany('App\Models\Answer')->orderBy('order')->orderBy('id');
    }

    /**
     * used by templates
     * @return mixed|string
     */
    public function getLabel() {
        $nr = 'Question #' . $this->order . ' : ';
        if (empty($this->head)) {
            if (empty($this->text)) {
                return 'Question without text';
            } else {
                return  $nr . substr(strip_tags($this->text), 0, 40) . '...';
            }
        } else {
            return $nr . $this->head;
        }
    }

    /**
     *  set the answer_count and is_valid values,
     *  and return the error string for 'Publish Scene'
     *
     * @return string
     */
    public function validityCheck() {

        $msg = '';
        $answer_count = 0;
        $correct_answers = 0;
        foreach($this->answers as $answer) {
            $answer_count++;
            if ($answer->is_correct) {
                $correct_answers++;
            }
        }
        $this->answer_count = $answer_count;

        $min_answers = 0;
        $min_correct = 0;
        $max_correct = 0;
        switch ($this->question_type_id) {
            case 1:
                $min_answers = 2;
                $min_correct = 1;
                $max_correct = 1;
                break;
            case 2:
                $min_answers = 3;
                $min_correct = 1;
                $max_correct = $answer_count - 1;
                break;
            case 3:
                $min_answers = 4;
                $min_correct = 2;
                $max_correct = $answer_count;
                break;
            default:
                abort(400, 'Unexpected question type.');
        }

        $this->is_valid = 0;
        if ($answer_count < $min_answers) {
            $msg = "Question {$this->order} needs more answers.";
        } elseif ($correct_answers < $min_correct) {
            $msg = "Question {$this->order} needs more correct answers.";
        } elseif ($correct_answers > $max_correct) {
            $msg = "Question {$this->order} has too many correct answers.";
        } else {
            $this->is_valid = 1;
        }

        $this->save();

        return $msg;
    }

}

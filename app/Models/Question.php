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
    protected $fillable = [ 'scene_id', 'question_type_id', 'order', 'head', 'text', 'explanation', 'points', 'answer_count'];

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
        return $this->hasMany('App\Models\Answer');
    }

}

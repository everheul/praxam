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
     * Appended Attribute with Accessors and Mutators (getters & setters).
     *
     * @var  array
     */
    protected $appends = ['locked', 'is_first', 'is_last'];

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
        return $this->belongsTo('App\Models\Scene', 'id', 'scene_id');
    }

    /**
     * The relation with question_types (OneToMany Inverse)
     */
    public function questionType() {
        return $this->belongsTo('App\Models\QuestionType', 'id', 'question_type_id');
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

    //------- Accessors and Mutators ------

    /** locked Accessor
     * @return  bool
     */
    public function getLockedAttribute() {
        if (empty($this->attributes['locked'])) {
            $this->attributes['locked'] = false;
        }
        return $this->attributes['locked'];
    }

    /** locked Mutator
     * @param  bool  $locked
     */
    public function setLockedAttribute($locked) {
        $locked = boolval($locked);
        $this->attributes['locked'] = $locked;
        if (!empty($this->answers)) {
            foreach ($this->answers as $answer) {
                $answer->setLockedAttribute($locked);
            }
        }
    }

    /**
     * @return  bool
     */
    public function getIsFirstAttribute() {
        if (empty($this->attributes['is_first'])) {
            $this->attributes['is_first'] = false;
        }
        return $this->attributes['is_first'];
    }

    /**
     * @param  bool  $is_first
     */
    public function setIsFirstAttribute($is_first) {
        $this->attributes['is_first'] = boolval($is_first);
    }

    /**
     * @return  bool
     */
    public function getIsLastAttribute() {
        if (empty($this->attributes['is_last'])) {
            $this->attributes['is_last'] = false;
        }
        return $this->attributes['is_last'];
    }

    /**
     * @param  bool  $is_last
     */
    public function setIsLastAttribute($is_last) {
        $this->attributes['is_last'] = boolval($is_last);
    }

}

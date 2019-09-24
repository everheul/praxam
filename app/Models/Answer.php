<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Answer extends Model
{
    use SoftDeletes;
    
    /**
     * @var array
     */
    protected $fillable = ['text', 'is_correct', 'order', 'correct_order'];

    /**
     * The relation with questions (OneToMany Inverse)
     */
    public function question() {
        return $this->belongsTo('App\Models\Question', 'id', 'question_id');
    }

    /**
     * The relation with useranswers (OneToMany)
     */
    public function useranswers() {
        return $this->hasMany('App\Models\UserAnswer', 'answer_id', 'id');
    }

    /**
     * Appended Attributes with their Accessors and Mutators (getters & setters).
     * These are used to help the template display a locked question the way it was answered.
     *
     * @var  array
     */
    protected $appends = ['checked','locked','order'];

    /** checked Accessor
     * @return  bool
     */
    public function getCheckedAttribute() {
        if (empty($this->attributes['checked'])) {
            $this->attributes['checked'] = false;
        }
        return $this->attributes['checked'];
    }

    /** checked Mutator
     * @param  bool  $checked
     */
    public function setCheckedAttribute($checked) {
        $this->attributes['checked'] = boolval($checked);
    }

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
        $this->attributes['locked'] = boolval($locked);
    }

    /** order Accessor (0 if no order necessary, 1 or more if there is)
     * @return  bool
     */
    public function getOrderAttribute() {
        if (empty($this->attributes['order'])) {
            $this->attributes['order'] = 0;
        }
        return $this->attributes['order'];
    }

    /** order Mutator
     * @param  bool  $order
     */
    public function setOrderAttribute($order) {
        $this->attributes['order'] = intval($order);
    }

    /**
     * Return the string that disables the inputboxes/radioboxes if locked
     * @return string
     */
    public function disabled() {
        return ($this->getLockedAttribute()) ? 'disabled' : '';
    }

    /**
     * Return the string that selects the inputboxes/radioboxes if checked
     * @return  string
     */
    public function checked() {
        return ($this->getCheckedAttribute()) ? "checked=\"checked\"" : "";
    }

    /**
     * Returns the css class to color the answer correct or wrong.
     * @return string
     */
    public function coolness() {
        if ($this->locked) {
            $cool = is_null($this->correct_order) ? $this->is_correct : $this->correct_order;
            return $cool ? 'correct' : 'wrong';
        }
        return '';
    }


    /** todo: change ajax to post
     * To be able to check the given answers in javascript, the <input> objects keep a clou in 'iscool'.
     * To make that clue difficult to read, it is stirred in a random string with the answer_id as key.
    **/
    public function is_cool() {
        $cool = is_null($this->correct_order) ? $this->is_correct : $this->correct_order;
        // hide the correct answer in a random decimal string at position [answer_id & 7] - then make it all hex
        $num = rand(123411111,999999999);
        $s = "$num";
        // skip first pos, use 1-8
        $pos = ($this->id & 7) + 1;
        $s[$pos] = "$cool";
        $t = dechex((int)$s);
        return $t;
    }


}

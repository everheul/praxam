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
    protected $appends = ['checked','locked','order'];

    public function getCheckedAttribute() {
        if (empty($this->attributes['checked'])) {
            $this->attributes['checked'] = false;
        }
        return $this->attributes['checked'];
    }

    public function setCheckedAttribute($checked) {
        $this->attributes['checked'] = boolval($checked);
    }

    public function getLockedAttribute() {
        if (empty($this->attributes['locked'])) {
            $this->attributes['locked'] = false;
        }
        return $this->attributes['locked'];
    }

    public function setLockedAttribute($locked) {
        $this->attributes['locked'] = boolval($locked);
    }

    public function getOrderAttribute() {
        if (empty($this->attributes['order'])) {
            $this->attributes['order'] = 0;
        }
        return $this->attributes['order'];
    }

    public function setOrderAttribute($order) {
        $this->attributes['order'] = intval($order);
    }

    public function disabled() {
        return ($this->getLockedAttribute()) ? 'disabled=1' : '';
    }

    public function checked() {
        return ($this->getCheckedAttribute()) ? 'checked=1' : '';
    }

    public function coolness() {
        if ($this->locked) {
            $cool = is_null($this->correct_order) ? $this->is_correct : $this->correct_order;
            return $cool ? 'correct' : 'wrong';
        }
        return '';
    }
**/

}

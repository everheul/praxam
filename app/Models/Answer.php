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
     * used by templates
     * @return mixed|string
     */
    public function getLabel() {
        $label = "Answer #{$this->order} : ";
        if (strlen($this->text) > 40) {
            $label .= substr($this->text, 0, 40) . '...';
        } else {
            $label .= $this->text;
        }
        return $label;
    }
}

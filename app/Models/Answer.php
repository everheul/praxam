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
    protected $fillable = ['text', 'is_correct', 'order', 'correct_order', 'question_id'];

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

}

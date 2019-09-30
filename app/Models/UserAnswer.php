<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserAnswer extends Model
{
    public $timestamps = false; 

    public $answer = null;

    protected $table = 'useranswers';

    //- fields that may be filled by create() and update();
    //  all others will be ignored without warning (!)
    protected $fillable = [ 'userquestion_id', 'answer_id', 'order'];

    /**
     * The relation with userquestions (OneToMany Inverse)
     */
    public function userquestion() {
        return $this->belongsTo('App\Models\UserQuestion', 'id', 'userquestion_id');
    }

    /**
     * The relation with answers (OneToMany Inverse)
     * todo: used?
     */
    public function answer() {
        return $this->belongsTo('App\Models\Answers', 'id', 'answer_id');
    }

    public function setAnswer(\App\Models\Answer $answer) {
        $this->answer = $answer;
    }
}

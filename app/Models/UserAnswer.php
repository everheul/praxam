<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserAnswer extends Model
{

    protected $table = 'useranswers';

    /**
     * The relation with userquestions (OneToMany Inverse)
     */
    public function userquestions() {
        return $this->belongsTo('App\Models\UserQuestion', 'id', 'userquestion_id');
    }

    /**
     * The relation with answers (OneToMany Inverse)
     * todo: used?
     */
    public function answers() {
        return $this->belongsTo('App\Models\Answers', 'id', 'answer_id');
    }

    /**
     * Initiate and save the useranswer
     *
     * @param $uqid
     * @param $aid
     * @param $order
     * @return $this
     */
    public function create($uqid, $aid, $order) {
        $this->userquestion_id = $uqid;
        $this->answer_id = $aid;
        $this->order = $order;
        $this->save();
        return $this;
    }

}

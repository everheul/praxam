<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Scene;


/**
 * Class UserScene
 * @package App\Models
 * 
 * Register the userexams scenes.
 */

class UserScene extends Model
{
    
    public $timestamps = false; // same as userquestions, made with userexam.

    protected $table = 'userscenes';

    //- fields that may be filled by create() and update();
    //  all others will be ignored without warning (!)
    protected $fillable = [ 'userexam_id', 'scene_id', 'order', 'result', 'locked'];

    /**
     * The relation with userexams (OneToMany Inverse)
     */
    public function userexam() {
        return $this->belongsTo('App\Models\UserExam', 'userexam_id', 'id');
    }

    /**
     * The relation with scenes (OneToMany Inverse)
     */
    public function scene() {
        return $this->belongsTo('App\Models\Scene', 'scene_id', 'id');
    }

    /**
     * The relation with userquestions (OneToMany)
     */
    public function userquestions() {
        return $this->hasMany('App\Models\UserQuestion', 'userscene_id', 'id');
    }

    public function userquestion($index = 0) {
        $this->loadMissing('userquestions');
        return isset($this->userquestions[$index]) ? $this->userquestions[$index] : NULL;
    }

    /**
     * Calc and store the result (questions result total)
     * ..or null if one or more questions were not answered yet.
     *
     * Note: this will lock the exam if all questions are answered.
     *
     * Called from UserQuestionController
     */
    public function checkResult() {
        if (empty($this->locked)) {
            $tot = 0;
            $locked = 1;

            $userquestions = $this->userquestions()->select('result')->get();
            foreach ($userquestions as $userquestion) {
                if (is_null($userquestion->result)) {
                    $locked = 0;
                } else {
                    $tot += $userquestion->result;
                }
            }

            $this->result = $tot;
            $this->locked = $locked;
            $this->update();
        }
    }

}

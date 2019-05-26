<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Scene;


/**
 * Class UserScene
 * @package App\Models
 *
    `id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
    `userscene_id` INT(10) UNSIGNED NOT NULL DEFAULT '0',
    `question_id` INT(10) UNSIGNED NOT NULL DEFAULT '0',
    `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    `order` TINYINT(3) UNSIGNED NOT NULL DEFAULT '0',
    `result` SMALLINT(6) NULL DEFAULT NULL,
 *
 * Register the userexams scenes.
 */

class UserScene extends Model
{
    public $timestamps = false;

    protected $table = 'userscenes';

    /**
     * The relation with userexams (OneToMany Inverse)
     */
    public function userexams() {
        return $this->belongsTo('App\Models\UserExam', 'id', 'userexam_id');
    }

    /**
     * The relation with scenes (OneToMany Inverse)
     */
    public function scenes() {
        return $this->belongsTo('App\Models\Scene', 'id', 'scene_id');
    }

    /**
     * The relation with userquestions (OneToMany)
     */
    public function userquestions() {
        return $this->hasMany('App\Models\UserQuestion', 'userscene_id', 'id');
    }


    /**
     * Initiate and save this model to get a valid id.
     *
     *      $userscene = (new UserScene)->create($userexamid, $sceneid, $order);
     *      $id = $userscene->id;
     *
     * @param int $userexamid
     * @param int $sceneid
     * @param int $order
     * @return $this
     */
    public function create($userexamid, $sceneid, $order) {
        $this->userexam_id = $userexamid;
        $this->scene_id = $sceneid;
        $this->order = $order;
        $this->save();
        return $this;
    }

    /**
     * Calc and store the result (question result total)
     * ..or null if one or more questions were not answered yet.
     *
     * Called from AjaxController.
     */
    public function checkResult() {
        if (is_null($this->result)) {
            $result = 0;
            $questions = $this->userquestions()->select('result')->get();
            foreach ($questions as $question) {
                if (is_null($question->result)) {
                    return null;
                }
                $result += $question->result;
            }
            $this->result = $result;
        }
        $this->save();
        return $this->result;
    }
}

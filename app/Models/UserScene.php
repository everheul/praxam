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
    
    public $timestamps = false; // same as userexam or questions

    protected $table = 'userscenes';

    //- fields that may be filled by create() and update();
    //  all others will be ignored without warning (!)
    protected $fillable = [ 'userexam_id', 'scene_id', 'order'];

    /**
     * The relation with userexams (OneToMany Inverse)
     */
    public function userexam() {
        return $this->belongsTo('App\Models\UserExam', 'id', 'userexam_id');
    }

    /**
     * The relation with scenes (OneToMany Inverse)
     */
    public function scene() {
        return $this->belongsTo('App\Models\Scene', 'id', 'scene_id');
    }

    /**
     * The relation with userquestions (OneToMany)
     */
    public function userquestions() {
        return $this->hasMany('App\Models\UserQuestion', 'userscene_id', 'id');
    }


    /**
     * Initiate and save this userscene:
     *      $userscene = (new UserScene)->create($userexamid, $sceneid, $order);
     *      $id = $userscene->id;
     *
     * @param int $userexamid
     * @param int $sceneid
     * @param int $order
     * @return $this
    public function create($userexamid, $sceneid, $order) {
        $this->userexam_id = $userexamid;
        $this->scene_id = $sceneid;
        $this->order = $order;
        $this->save();
        return $this;
    }
*/

    /**
     * Calc and store the result (questions result total)
     * ..or null if one or more questions were not answered yet.
     *
     * Called from AjaxController. todo
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
            $this->locked = 1;
        }
        $this->update();
        return $this->result;
    }

}

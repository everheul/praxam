<?php

/**
 * This is in fact a pivot table between users and exams,
 * but it has to hold a few things more so a Model seemed more practical.
 *
 *  CREATE TABLE `userexams` (
    `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
    `exam_id` INT(11) UNSIGNED NOT NULL,
    `user_id` INT(11) UNSIGNED NOT NULL,
    `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `finished_at` DATETIME NULL DEFAULT NULL,
    `result` INT(10) UNSIGNED NULL DEFAULT NULL,
    PRIMARY KEY (`id`)
)
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use DB;

class UserExam extends Model
{
    use SoftDeletes;

    //- no standard name for the user tables, for better readability.
    protected $table = 'userexams';

    //- fields that may be filled by create() and update();
    //  all others will be ignored without warning (!)
    protected $fillable = [ 'user_id', 'exam_id', 'scene_count', 'finished_at', 'result'];

    /**
     * The relation with userscenes (OneToMany)
     */
    public function userscenes() {
        return $this->hasMany('App\Models\UserScene', 'userexam_id', 'id');
    }

    /**
     * The relation with exams (OneToMany Inverse)
     */
    public function exam() {
        return $this->belongsTo('App\Models\Exam');
    }

    /**
     * The relation with users (OneToMany Inverse)
     */
    public function user() {
        return $this->belongsTo('App\Models\User');
    }
    
    /**
     * re-calculate the total result on this exam
     */
    public function checkResult()
    {
        $result = DB::table('userscenes')
            ->where('userexam_id', '=', $this->id)
            ->whereNotNull('result')
            ->sum('userscenes.result');
        $this->result = $result;
        
        $scenes_left = DB::table('userscenes')
            ->where('userexam_id', '=', $this->id)
            ->where('locked',0)
            ->count();
        if ($scenes_left === 0) {
            $this->finished_at = DB::raw('now()');
        }
        $this->update();
    }

    /** todo: obsolete?
     * Jump to the next (or first) scene of this userexam that still has to be answered
     */
    public function nextScene($order = 0) {

        $userscene = UserScene::where('userexam_id', $this->id)
            ->where('locked',0)
            ->where('order','>',$order)
            ->orderBy('order')
            ->select('order')
            ->first();

        if (empty($userscene)) {
            $userscene = UserScene::where('userexam_id', $this->id)
                ->where('locked',0)
                ->orderBy('order')
                ->select('order')
                ->first();
        }

        if (empty($userscene)) {
            //- no unlocked scenes left, test finished. Show result:
            return redirect(url("/prax/{$this->id}"));
        }

        return redirect(url("/prax/{$this->id}/scene/{$userscene->order}"));
    }

    
}

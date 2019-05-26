<?php

/**
 * This is in fact a pivot table between users and exams,
 * but it has to hold a few things more so a Model seemed more practical..?
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


class UserExam extends Model
{

    protected $table = 'userexams';

    /**
     * The relation with userscenes (OneToMany)
     */
    public function userscenes() {
        return $this->hasMany('App\Models\UserScene', 'userexam_id', 'id');
    }

    /**
     * The relation with exams (OneToMany Inverse)
     */
    public function exams() {
        return $this->belongsTo('App\Models\Exam', 'id', 'exam_id');
    }

    /**
     * The relation with users (OneToMany Inverse)
     */
    public function users() {
        return $this->belongsTo('App\Models\User', 'id', 'user_id');
    }


    public function create($userid, $examid, $scene_count) {
        $this->user_id = $userid;
        $this->exam_id = $examid;
        $this->scene_count = $scene_count;
        $this->save();
        return $this;
    }

}

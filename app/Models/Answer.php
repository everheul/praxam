<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Answer
 * @package App\Models
 *
 * One of the multiple-choice answers of a question.
 *
    CREATE TABLE `answers` (
        `id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
        `question_id` INT(10) UNSIGNED NOT NULL DEFAULT '0',
        `text` VARCHAR(1024) NULL DEFAULT NULL,
        `is_correct` TINYINT(3) UNSIGNED NOT NULL DEFAULT '0',
        `order` TINYINT(3) UNSIGNED NOT NULL DEFAULT '0',
        `correct_order` TINYINT(3) UNSIGNED NOT NULL DEFAULT '0' COMMENT '..if question type = 3',
        PRIMARY KEY (`id`)
    )
    COLLATE='utf8mb4_unicode_ci'
    ENGINE=MyISAM
    ;
 *
 */

class Answer extends Model
{
    //- attributes
    private $checked = false;

    /**
     * @var array
     */
    //protected $fillable = ['text', 'is_correct', 'order', 'correct_order'];

    /**
     * The relation with questions (OneToMany Inverse)
     */
    public function questions() {
        return $this->belongsTo('App\Models\Question', 'id', 'question_id');
    }

    /**
     * The relation with useranswers (OneToMany)
     */
    public function answerUser() {
        return $this->hasMany('App\Models\UserAnswer', 'answer_id', 'id');
    }

    /**
     * todo: switch question_type?
     */
    public function disable() {
        $this->disabled = true;
    }

    public function disabled() {
        return ($this->disabled) ? 'disabled' : '';
    }

    public function coolness() {
        if ($this->disabled) {
            $cool = is_null($this->correct_order) ? $this->is_correct : $this->correct_order;
            return $cool ? 'correct' : 'wrong';
        }
        return '';
    }

    /**
     * todo: add check_order and question_type
     */
    public function check() {
        $this->checked = true;
    }

    public function checked() {
        return ($this->checked) ? "checked=\"checked\"" : "";
    }

    /**
     * To be able to check the given answers in javascript, the <input> objects keep a clou in 'iscool'.
     * To make that clue difficult to read, it is stirred in a random string with the answer_id as key.
    **/
    public function is_cool() {
        $cool = is_null($this->correct_order) ? $this->is_correct : $this->correct_order;
        // hide the correct answer in a random decimal string at position [answer_id & 7] - then make it all hex
        $num = rand(123411111,999999999);
        $s = "$num";
        // skip first pos, use 1-8
        $pos = ($this->id & 7) + 1;
        $s[$pos] = "$cool";
        $t = dechex((int)$s);
        return $t;
    }


}

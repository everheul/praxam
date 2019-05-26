<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Collection as Collection;
use App\Models\Answer;

/**
 * Class Question
 * @package App\Models
 *
 * Every question belongs to exactly 1 scene and is the owner of some (multiple-choice) answers.
 *
 * TABLE `questions` (
 * `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
 * `scene_id` INT(11) UNSIGNED NOT NULL DEFAULT '0',
 * `question_type_id` INT(11) UNSIGNED NOT NULL DEFAULT '0',
 * `order` SMALLINT(5) UNSIGNED NOT NULL DEFAULT '0' COMMENT 'if more questions per scene',
 * `head` VARCHAR(191) NULL DEFAULT NULL COLLATE 'utf8mb4_unicode_ci',
 * `text` VARCHAR(5000) NULL DEFAULT NULL COLLATE 'utf8mb4_unicode_ci',
 * `explanation` VARCHAR(5000) NULL DEFAULT NULL COLLATE 'utf8mb4_unicode_ci',
 * `points` SMALLINT(6) NULL DEFAULT '0',
 * `answer_count` TINYINT(4) UNSIGNED NULL DEFAULT '0',
 * `old_question_id` INT(11) UNSIGNED NOT NULL DEFAULT '0',
 *
 *  question_type_id:
 *  1 - One correct answer only (radioboxes)
 *  2 - One or more correct answers (checkboxes)
 *  3 - Two or more correct answers in the correct order (two sortables)
 *  4 - ?
 *
 */
class Question extends Model
{
    //- property
    private $locked = false;

    /**
     * @var array
     */
    //protected $fillable = [ 'question_type_id', 'order', 'head', 'text', 'explanation', 'points'];


    /**
     * The relation with scenes (OneToMany Inverse)
     */
    public function scenes() {
        return $this->belongsTo('App\Models\Scene', 'id', 'scene_id');
    }

    /**
     * The relation with question_types (OneToMany Inverse)
     */
    public function questionTypes() {
        return $this->belongsTo('App\Models\QuestionType', 'id', 'question_type_id');
    }

    /**
     * The relation with userquestions (OneToMany)
     */
    public function userquestions() {
        return $this->hasMany('App\Models\UserQuestion', 'question_id', 'id');
    }

    /**
     * The relation with answers (OneToMany Inverse)
     */
    public function answers() {
        return $this->hasMany('App\Models\Answer');
    }


    /**
     * the answers belonging to this question, cached in correct order
     * @return Collection
    public function getAnswers(): Collection {
        if (empty($this->answers)) {
            $this->answers = $this->answers()
                ->orderBy('order')
                ->get();
        }
        return $this->answers;
    }
    */

    public function lock() {
        $this->locked = true;
        foreach($this->answers as $answer) {
            $answer->disable();
        }
    }

    public function locked() {
        return $this->locked;
    }


}

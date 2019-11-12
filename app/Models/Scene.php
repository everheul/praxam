<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Collection as Collection;
use App\Models\Question as Question;
use App\Models\Exam as Exam;
use DB;
use Illuminate\Database\Eloquent\SoftDeletes;


/**
 * Class Scene
 * @package App\Models
 *
 * A Scene is the beginning of a testcase with one or more questions.
 * The fields 'head', 'overview', 'exhibit' (the path to a picture) and 'chapter' are optional and depend on 'scene_type_id'.
 *
 *  scene.type:
 *  1 - One question only.
 *  2 - Two ore more questions, four is default.
 *  3 - ?
 */
class Scene extends Model
{
    use SoftDeletes;

    /**
     * @var array
     */
    protected $fillable = ['exam_id', 'scene_type_id', 'chapter', 'head', 'text', 'image', 'instructions', 'is_public'];

    /**
     * The relation with exams
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function exam() {
        return $this->belongsTo('App\Models\Exam','exam_id','id');
    }

    /**
     * The relation with questions (OneToMany)
     */
    public function questions() {
        return $this->hasMany('App\Models\Question')->orderBy('order')->orderBy('id');
    }

    /**
     * The relation with userscenes (OneToMany)
     */
    public function userscenes() {
        return $this->hasMany('App\Models\UserScene', 'scene_id', 'id');
    }

    /**
     * the first (or only) question of this scene, used by templates.
     */
    public function question($order = 0): Question {
        return $this->questions[$order];
    }

    /**
     * The relation with scene-types (OneToMany Inverse)
     */
    public function sceneType() {
        return $this->belongsTo('App\Models\SceneType', 'scene_type_id', 'id');
    }

    /**
     * todo: set in db! obsolete?
     *
     * @param  Scene  $scene
     */
    public function setQuestionsOrder() {
        $n = 1;
        foreach($this->questions as &$question) {
            $question->order = $n++;
        }
    }

    /** todo: obsolete?
     * 
     * @param int $userwantsto
     * @return int
     */
    public function _canPublish(int $userwantsto) {
        if ($userwantsto) {
            //- user wants to publish
            switch($this->scene_type_id) {
                case 1:
                    return ($this->questions->count() > 0) ? 1 : 0;
                case 2:
                    return ($this->questions->count() > 1) ? 1 : 0;
            }
        }
        return 0;
    }

    /**
     * @return string
     */
    public function imageName() {
        if (!empty($this->image)) {
            $pos = strpos($this->image,'_');
            if (!empty($pos)) {
                return substr($this->image, $pos + 1);
            }
        }
        return '';
    }
    
    /**
     *  set the question_count and is_valid values,
     *  and return the error string for 'Publish Scene'
     *
     * @return string
     */
    public function validityCheck() {
        $errmsg = '';

        $this->loadMissing('questions','questions.answers');

        $min_questions = 0;
        switch ($this->scene_type_id) {
            case 1:
                $min_questions = 1;
                break;
            case 2:
                $min_questions = 2;
                break;
            default:
                abort(400, 'Unexpected scene type.');
        }

        $this->question_count = 0;
        $this->is_valid = 1;
        foreach($this->questions as $question) {
            $this->question_count++;
            $errmsg = $question->validityCheck();
            if (!$question->is_valid) {
                $this->is_valid = 0;
            }
        }

        if ($this->question_count < $min_questions) {
            $q = ($min_questions > 1) ? "$min_questions questions" : "1 question";
            $errmsg = "You need at least $q to publish this scene.";
            $this->is_valid = 0;
        }

        if (!$this->is_valid) {
            $this->is_public = 0;
        }

        return $errmsg;
    }


}

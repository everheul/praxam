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
    protected $fillable = ['exam_id','scene_type_id', 'chapter', 'head', 'text', 'image'];

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
        return $this->hasMany('App\Models\Question', 'scene_id', 'id')->orderBy('order')->orderBy('id');
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
     * todo: set in db!
     *
     * @param  Scene  $scene
     */
    public function setQuestionsOrder() {
        $n = 1;
        foreach($this->questions as &$question) {
            $question->order = $n++;
        }
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
     * 
     * @return boolean
     */
    public function isValid() {
        
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
        $question_count = 0;
        $is_valid = 1;
        foreach($this->questions as $question) {
            if (!$question->isValid()) {
                $is_valid = 0;
            } else {
                $question_count++;
            }
        }
        if ((!$is_valid) || ($question_count < $min_questions)) {
            if ($this->is_valid) {
                $this->is_valid = 0;
                $this->save();
            }
            var_dump("invalid scene: {$this->id}");
            return false;
        } else {
            if (!$this->is_valid) {
                $this->is_valid = 1;
                $this->save();
            }
            return true;
        }
    }


}

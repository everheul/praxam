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

    protected $appends = ['locked'];


    /**
     * @var array
     */
    protected $fillable = ['scene_type_id', 'chapter', 'head', 'text', 'image'];

    /**
     * The relation with exams (ManyToMany, using exam_scene)
     */
    public function exams() {
        return $this->belongsToMany('App\Models\Exam', 'exam_scene');
    }
    
    /**
     * The relation with questions (OneToMany)
     */
    public function questions() {
        return $this->hasMany('App\Models\Question', 'scene_id', 'id');
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
        return $this->belongsTo('App\Models\SceneType', 'id', 'scene_type_id');
    }

    /**
     * The scene-type-name (OneToMany Inverse)
     */
    public function sceneTypeName() {
        return $this->belongsTo('App\Models\SceneType', 'id', 'scene_type_id')->select('name');
    }

    /**
     * The relation with userscenes (OneToMany)
     */
    public function userscenes() {
        return $this->hasMany('App\Models\UserScene', 'scene_id', 'id');
    }



    /**
     * the questions belonging to this scene, cached in correct order
     * @return Collection
     *
    public function getQuestions(): Collection {
        if (empty($this->questions)) {
            $this->questions = $this->questions()
                ->orderBy('order')
                ->get();
            $this->setFirstLast();
        }
        return $this->questions;
    }

    /**
     * let the accordion know what's what
     *
    private function setFirstLast() {
        $last = count($this->questions);
        $n = 1;
        foreach($this->questions as &$question) {
            $question->isFirst = ($n == 1) ? true : false;
            $question->isLast = ($n == $last) ? true : false;
            $n += 1;
         }
    }

    /**
     * Get the id of the next scene of the given exam
     * 
     * @return int
     *
    public function nextSceneId($examId) {
        $nextScene = DB::table('exam_scenes')
            ->select('scene_id')
            ->where('exam_id','=',$examId)
            ->where('scene_id','>',$this->id)
            ->orderBy('scene_id')
            ->first();
        if (empty($nextScene)) {
            $nextScene = DB::table('exam_scenes')
                ->select('scene_id')
                ->where('exam_id','=',$examId)
                ->orderBy('scene_id')
                ->first();
        }
        return (empty($nextScene)) ? 0 : $nextScene->id;
    }
*/
}

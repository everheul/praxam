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
 * The 'head', 'overview', 'exhibit' (the path to a picture) and 'chapter' are optional and depending on type.
 *
 *  scene.type:
 *  1 - One question only.
 *  2 - Two ore more questions, four is default.
 *  3 - ?
 */
class Scene extends Model
{
    use SoftDeletes;

    //private $questions = [];

    /**
     * @var array
     */
    //protected $fillable = ['scene_type_id', 'chapter', 'head', 'text', 'image'];

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
     * The relation with scene-types (OneToMany Inverse)
     */
    public function sceneType() {
        return $this->belongsTo('App\Models\SceneType', 'id', 'scene_type_id');
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
     */
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
     * the first (only?) question of this scene
     */
    public function first_question(): Question {
        return $this->getQuestions()[0];
    }

    /**
     * let the accordion know what's what
     */
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
     * JSON list with question data to be used in template <scripts> sections.
     */
    public function questionTypes(): string {
        $a = [];
        $q = $this->getQuestions();
        foreach($q as $question) {
            $a[] = Array('id' => $question->id,
                    'type' => $question->question_type_id,
                    'first' => $question->isFirst,
                    'last' => $question->isLast);
        }
        // this might return false?
        return json_encode($a);
    }

    /**
     * Get the id of the next scene.
     * todo: select next scene of the same exam
     * 
     * @return int
     */
    public function nextSceneId() {
        $nextScene = DB::table('scenes')->select('id')->where('id','>',$this->id)->orderBy('id')->first();
        if (empty($nextScene)) {
            $nextScene = DB::table('scenes')->select('id')->orderBy('id')->first();
        }
        return (empty($nextScene)) ? 0 : $nextScene->id;
    }
}

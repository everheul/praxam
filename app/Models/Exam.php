<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Exam extends Model
{
    use SoftDeletes;

    /**
     * @var array
     */
    protected $fillable = ['name', 'head', 'intro', 'text', 'image'];

    /**
     * The relation with userexams (OneToMany)
     */
    public function userexams() {
        return $this->hasMany('App\Models\UserExam');
    }

    /**
     * The relation with scenes (ManyToMany, using exam_scene)
     * can be disabled by DBA by setting 'active' to 0
     */
    public function scenes() {
        return $this->belongsToMany('App\Models\Scene')->withPivot('exam_scene')->where('active', 1);
    }


}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Exam extends Model
{
    use SoftDeletes;

    protected $dates = ['deleted_at'];

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
     * can be disabled by DBA ;)
     */
    public function scenes() {
        return $this->belongsToMany('App\Models\Scene'); // ->where('pivot.active', 1)
    }


}

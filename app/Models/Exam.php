<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use DB;
use App\Models\User;

class Exam extends Model
{
    use SoftDeletes;

    /**
     * @var array
     */
    protected $fillable = ['created_by', 'name', 'head', 'intro', 'text', 'image'];

    /**
     * The relation with userexams (OneToMany)
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function userexams() {
        return $this->hasMany('App\Models\UserExam');
    }

    /**
     * The relation with scenes (OneToMany)
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function scenes() {
        return $this->hasMany('App\Models\Scene');
    }

    /**
     *
     * @return  int  $count
     */
    public function user_count() {
        $count = DB::table('userexams')
            ->where('exam_id', $this->id)
            ->distinct('user_id')
            ->count('user_id');
        return $count;
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
     * @return bool
     */
    public function canEdit($user) {
        return (($user->isAdmin()) || ($user->id === $this->created_by));
    }
    
    /**
     * 
     * @return boolean
     */
    public function isValid() {
        
        $min_scenes = 5;
        $scene_count = 0;
        foreach($this->scenes as $scene) {
            if ($scene->isValid()) {
                $scene_count++;
            }
        }
        if ($scene_count < $min_scenes) {
            if ($this->is_valid) {
                $this->is_valid = 0;
                $this->save();
            }
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

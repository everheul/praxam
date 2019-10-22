<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SceneType extends Model
{
    protected $table = 'scene_types';

    /**
     * The relation with scenes (OneToMany)
     */
    public function scenes() {
        return $this->hasMany('App\Models\Scenes', 'scene_type_id', 'id');
    }
}

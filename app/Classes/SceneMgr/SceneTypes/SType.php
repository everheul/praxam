<?php

namespace App\Classes\SceneMgr\SceneTypes;

abstract class Stype
{
    protected $scene;

    protected $templates = [
        'create' => ''
    ];

    public function __construct(Scene $scene) {
        $this->scene = $scene;
    }


}
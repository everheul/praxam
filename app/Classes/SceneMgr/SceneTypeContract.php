<?php
/**
 * Created by PhpStorm.
 * User: Ekke Verheul
 * Date: 28-Jan-20
 * Time: 10:11
 */

namespace App\Classes\SceneMgr;

interface SceneTypeContract
{

    public function edit(Exam $exam, Scene $scene, $active_question = 1);

    public function preview(Exam $exam, Scene $scene, $active_question = 1);

    public function quiz(UserExam $exam, UserScene $scene, $active_question = 1);

}

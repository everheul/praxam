<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Helpers\Sidebar;
use App\Models\Exam;
use App\Models\UserQuestion;
use App\Models\UserExam;
use App\Models\UserScene;
use DB;

class UserExamController extends Controller
{
    public function __construct() {
        $this->middleware('auth');
    }

    /**
     * Display a list of the tests created by user?
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new UserExam of Exam $examid.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($examid) {
        $exam = Exam::findOrFail($examid);
        $sidebar = (new Sidebar)->editUserExam($exam);
        return view('examuser.create',['sidebar' => $sidebar, 'exam' => $exam]);
    }

    /**
     * POST
     * Make a new Practice Exam: a UserExam with UserScenes and UserQuestions.
     * The UserAnswers will be created after answering the questions.
     *
     * @param  int  $examid
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store($examid, Request $request) {
        // todo: check the route $examid with $request->examid ?
        $scene_count = $request->scene_count;
        $userexamid = (new UserExam())->create($request->user()->id, $examid, $scene_count)->id;
        $scenes = $this->getRandomScenes($examid, $scene_count);
        $this->makeUserExamScenes($userexamid, $scenes);
        return redirect(url("/examu/$userexamid/sceneu/1/show"));
    }

    private function getRandomScenes($examid, $scene_count) {
        //- todo: pick the scenes for a Practice Exam in a more sophisticated way,
        //- like the ones never done before, or failed to answer correctly.
        return DB::table('scenes')
            ->leftJoin('exam_scene', 'scenes.id', '=', 'exam_scene.scene_id')
            ->where('exam_scene.exam_id', '=', $examid)
            ->select('id')
            ->inRandomOrder()
            ->limit($scene_count)->get();
    }

    private function makeUserExamScenes($userexamid, $scenes) {
        $order = 1;
        foreach($scenes as $scene) {
            $us = (new UserScene())->create($userexamid, $scene->id, $order++);
            $questions = $this->getSceneQuestionIds($scene);
            foreach($questions as $question) {
                (new UserQuestion())->create($us->id, $question->id);
            }
        }
    }

    private function getSceneQuestionIds($scene) {
        return DB::table('questions')->where('scene_id', '=', $scene->id)->orderBy('order')->select('id')->get();
    }

}

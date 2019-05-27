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
    public function index() {
        dd(" -= TODO =- ");
    }

    /**
     * Display all the info (result!) of the specified userexam.
     * TODO
     *
     * @param  int $id
     * @return \\Illuminate\Http\Response
     */
    public function show($prax_id) {
        $userexam = UserExam::where('id', '=', $prax_id)->with('exams')->firstOrFail();
        return View('userexam.show',
            [   'sidebar' => (new Sidebar)->examOverview($userexam),
                'userexam' => $userexam ]
        );
    }


    /**
     * Show the form for creating a new UserExam.
     * NOTE: This needs an Exam $exam_id !
     *
     * @return \Illuminate\Http\Response
     */
    public function create($exam_id) {
        $exam = Exam::findOrFail($exam_id);
        $sidebar = (new Sidebar)->editUserExam($exam);
        return view('examuser.create',['sidebar' => $sidebar, 'exam' => $exam]);
    }

    /**
     * POST
     *
     * Make a new Practice Exam: a UserExam with UserScenes and UserQuestions.
     * The UserAnswers will be created after answering the questions.
     * NOTE: This gets an Exam $exam_id too!
     *
     * @param  int  $exam_id
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $exam_id) {
        // todo: check the route $examid with $request->examid ?
        $scene_count = $request->scene_count;
        $userexamid = (new UserExam())->create($request->user()->id, $exam_id, $scene_count)->id;
        $scenes = $this->getRandomScenes($exam_id, $scene_count);
        $this->makeUserExamScenes($userexamid, $scenes);
        return redirect(url("/prax/$userexamid/scene/1"));
    }

    /**
     * Soft-delete the userexam.
     *
     * @param  int $prax_id
     * @return \\Illuminate\Http\Response
     */
    public function kill($prax_id) {
        $prax = Exam::findOrFail($prax_id);
        $prax->delete();
        return redirect(url("/prax"));
    }

    /**
     * @param  int  $exam_id
     * @param  int  $scene_count
     * @return  mixed
     */
    private function getRandomScenes($exam_id, $scene_count) {
        //- todo: pick the scenes for a Practice Exam in a more sophisticated way,
        //- like the ones never done before, or failed to answer correctly.
        return DB::table('scenes')
            ->leftJoin('exam_scene', 'scenes.id', '=', 'exam_scene.scene_id')
            ->where('exam_scene.exam_id', '=', $exam_id)
            ->select('id')
            ->inRandomOrder()
            ->limit($scene_count)->get();
    }

    private function makeUserExamScenes($prax_id, $scenes) {
        $order = 1;
        foreach($scenes as $scene) {
            $us = (new UserScene())->create($prax_id, $scene->id, $order++);
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

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Helpers\Sidebar;
use App\Models\Exam;
use App\Models\UserQuestion;
use App\Models\UserExam;
use App\Models\UserScene;
use DB;
use App\Http\Requests\NewPraxRequest;
use App\Classes\PraxExam;

class UserExamController extends Controller
{
    // used to check user-exam ownership once
    private $user_checked;


    public function __construct() {
        $this->middleware('auth');
    }

    /**
     * Display a list of the tests created by this user.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        return redirect(url("/home"));
    }

    /**
     * Display the Test Result of the specified userexam.
     *
     * @param  int $id
     * @return \\Illuminate\Http\Response
     */
    public function show(Request $request, $prax_id) {

        if (!$this->checkUser($request, $prax_id)) {
            return redirect(url("/home"));
        }

        $praxexam = $this->loadPraxExam($prax_id);

        return View('userexam.show',
            [   'sidebar' => (new Sidebar)->examResult($praxexam),
                'praxexam' => $praxexam ]
        );
    }


    /**
     * @param  int  $prax_id
     * @return  PraxExam|bool
     */
    private function loadPraxExam($prax_id) {
        $userExam = UserExam::where('id', '=', $prax_id)
            ->with('userscenes','userscenes.userquestions','userscenes.userquestions.useranswers')
            ->firstOrFail();
        $exam = (new ExamController())->getFullExam($userExam->exam_id);
        return (new PraxExam($exam))->setUserExam($userExam);
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
        return view('userexam.create',['sidebar' => $sidebar, 'exam' => $exam]);
    }

    /**
     * POST
     *
     * Make a new Practice Exam: a UserExam with UserScenes and UserQuestions.
     * The UserAnswers will be created after answering the questions.
     * NOTE: This needs an exam id; there's no prax id yet!
     *
     * @param  int  $exam_id
     * @param  NewPraxRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(NewPraxRequest $request, $exam_id) {
        // todo: check the route $examid with $request->examid ?

        $req_exam_id = $request->input('exam_id',$exam_id);
        $scene_type = $request->input('scene_type',0);
        $question_type = $request->input('question_type',0);
        $scene_count = $request->input('scene_count',10);

        $query = DB::table('scenes')
            ->join('exam_scene', function ($join) use ($req_exam_id) {
                $join->on('scenes.id', '=', 'exam_scene.scene_id')->where('exam_scene.exam_id', '=', $req_exam_id); })
            ->join('questions', 'scenes.id', '=', 'questions.scene_id');
        if ($scene_type > 0) {
            $query = $query->where('scenes.scene_type_id', '=', $scene_type);
        }
        if ($question_type > 0) {
            $query = $query->where('questions.question_type_id', '=', $question_type);
        }
        $scene_ids = $query->select('scenes.id')
            ->inRandomOrder()
            ->limit($scene_count)
            ->get();

        $nr = $scene_ids->count();

        if ($nr === 0) {
            return redirect()->back()->withInput()->withErrors('Sorry. No scenes found for this combination of types.');
        }

        if ($nr < $scene_count) {
            //- todo: add some more scenes!?
            $scene_count = $nr;
        }

        $user_exam_id = (new UserExam())->create(['user_id' => $request->user()->id, 'exam_id' => $exam_id, 'scene_count' => $scene_count])->id;

        //$scenes = $this->getRandomScenes($exam_id, $scene_count);

        $this->makeUserExamScenes($user_exam_id, $scene_ids);

        return redirect(url("/prax/$user_exam_id/scene/1"));
    }



    /**
     * Soft-delete the userexam.
     *
     * @param  int $prax_id
     * @return \\Illuminate\Http\Response
     */
    public function destroy($prax_id) {
        $prax = UserExam::findOrFail($prax_id);
        $prax->delete();
        return redirect(url("/home"));
    }

    /**
     * Jump to the next (or first) scene that still has to be answered
     */
    public function nextScene($prax_id, $order = 0) {

        $userscene = UserScene::where('userexam_id', $prax_id)
            ->where('locked',0)
            ->where('order','>',$order)
            ->orderBy('order')
            ->select('order')
            ->first();

        if (empty($userscene)) {
            $userscene = UserScene::where('userexam_id', $prax_id)
                ->where('locked',0)
                ->orderBy('order')
                ->select('order')
                ->first();
        }

        if (empty($userscene)) {
            //- no unlocked scenes left, test finished. Show result:
            return redirect(url("/prax/$prax_id"));
        }

        return redirect(url("/prax/$prax_id/scene/{$userscene->order}"));
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
            $us = (new UserScene())->create(['userexam_id'=>$prax_id, 'scene_id' => $scene->id, 'order' => $order++]);
            $questions = $this->getSceneQuestionIds($scene);
            foreach($questions as $question) {
                (new UserQuestion())->create(['userscene_id' => $us->id, 'question_id' => $question->id]);
            }
        }
    }

    private function getSceneQuestionIds($scene) {
        return DB::table('questions')->where('scene_id', '=', $scene->id)->orderBy('order')->select('id')->get();
    }


    /**
     * Make sure this userExam was created by THIS user.
     * todo: move to middleware
     *
     * @param Request $request
     * @param $prax_id
     * @return bool
     */
    private function checkUser(Request $request, $prax_id)
    {
        if (empty($this->user_checked)) {
            $user_exam = UserExam::where('id', $prax_id)->firstOrFail();
            $this->user_checked = ($user_exam->user_id === $request->user()->id);
            //- todo: log if false
        }
        return $this->user_checked;
    }

}

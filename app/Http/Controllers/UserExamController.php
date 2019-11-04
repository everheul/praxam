<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Helpers\Sidebar;
use App\Models\Exam;
use App\Models\UserQuestion;
use App\Models\UserExam;
use App\Models\UserScene;
use App\Models\Scene;
use DB;
use App\Http\Requests\NewPraxRequest;
use App\Classes\PraxExam;

class UserExamController extends Controller
{
    // used to check user-exam ownership once, todo: to Middleware
    private $user_checked;


    public function __construct() {
        $this->middleware('auth');
        $this->middleware('userexam_owner');
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
     * @param  Request  $request
     * @param  int $userexam_id
     * @return \\Illuminate\Http\Response
     */
    public function show(Request $request, $userexam_id) {
        $praxexam = (new PraxExam())->loadUserExamData($userexam_id);
        return View('userexam.show',
            [   'sidebar' => (new Sidebar)->sbarPraxResult($praxexam),
                'praxexam' => $praxexam ]
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
        $sidebar = (new Sidebar)->sbarPraxCreate($exam);
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

        $req_exam_id = $request->input('exam_id', $exam_id);
        $scene_type = $request->input('scene_type', 0);
        $question_type = $request->input('question_type',0);
        $scene_count = $request->input('scene_count',10);

        //- Build a new UserExam, with scenes.
        //- todo: pick the scenes for a new UserExam in a more sophisticated way.
        
        $query = Scene::where('exam_id', $req_exam_id)->where('is_valid','=',1);

        if ($scene_type > 0) {
            $query = $query->where('scenes.scene_type_id', '=', $scene_type);
        }

        if ($question_type > 0) {
            $query = $query->where('questions.question_type_id', '=', $question_type);
        }

        $scenes = $query->select('scenes.id')->where('is_valid','=',1)
            ->inRandomOrder()
            ->limit($scene_count)
            ->get();

        $nr = $scenes->count();

        if ($nr === 0) {
            return redirect()
                    ->back()
                    ->withInput()
                    ->withErrors('Sorry. No valid scenes found for this combination of types.');
        }

        if ($nr < $scene_count) {
            //- todo: add some other scenes!?
            $scene_count = $nr;
        }

        $user_exam_id = (new UserExam())->create(['user_id' => $request->user()->id, 'exam_id' => $exam_id, 'scene_count' => $scene_count])->id;

        //$scenes = $this->getRandomScenes($exam_id, $scene_count);

        $this->makeUserExamScenes($user_exam_id, $scenes->pluck('id'));

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
*/

    /**
    * //- todo: pick the scenes for a new Practice Exam in a more sophisticated way.
     *
     * @param int $userexam_id
     * @param array $scene_ids
     */
    private function makeUserExamScenes($userexam_id, $scene_ids) {
        //dd($scene_ids);
        $scene_order = 1;
        foreach($scene_ids as $scene_id) {
            $us = (new UserScene())->create(['userexam_id'=> $userexam_id, 'scene_id' => $scene_id, 'order' => $scene_order++]);
            $question_ids = DB::table('questions')->where('scene_id', '=', $scene_id)->orderBy('order')->orderBy('id')->select('id')->get()->pluck('id');
            // not using question order here, that may change, and the order has to be continues from 1.
            $question_order = 1;
            foreach($question_ids as $question_id) {
                (new UserQuestion())->create(['userscene_id' => $us->id, 'question_id' => $question_id, 'order' => $question_order++ ]);
            }
        }
    }

}

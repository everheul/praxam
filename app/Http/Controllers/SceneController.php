<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Scene as Scene;
use App\Models\Exam;
use App\Helpers\Helper as Helper;
use App\Helpers\Sidebar as Sidebar;
use DB;
use Illuminate\Support\Facades\URL;

class SceneController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('args2session')->only('index');
    }

    /**
     * Display a listing of the exam scenes.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, $exam_id)
    {
        //- all this needs 'args2session'
        $page_base = str_replace('/','.',$request->path());
        $this->registerPaginator($request,$page_base);
        $paginate = $request->session()->get('paginate', 10);
        $filter = $request->session()->get($page_base.'.filter', "");
        $lf = Helper::likeFilter($filter);
        $direction = $request->session()->get($page_base.'.direction', 'asc');
        $sortby = $request->session()->get($page_base.'.sortby', 'id');
        if (!in_array($sortby,['id','created_at','scene_type_id','head'])) $sortby = 'id';

        //DB::enableQueryLog();
        $exam = Exam::findOrFail($exam_id);
        $scenes = $exam->scenes()->
                where( function($q) use($lf) {
                    $q->where('head', 'LIKE', $lf)->
                        orWhere('text', 'LIKE', $lf)->
                        orWhere('instructions', 'LIKE', $lf);
                })->
                orderBy($sortby, $direction)->
                paginate($paginate);
        //dd(DB::getQueryLog());
        $sidebar = (new Sidebar)->editExamScenes($exam);
        $data = compact('exam','scenes','paginate','filter','sortby','direction','sidebar');

        return view('scene.index', $data);
    }

    /**
     * Show the scene. Admin mode, action IGNORE.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($exam_id, $scene_id) {
        //$scene = Scene::findOrFail($scene_id);
        $scene = $this->getFullScene($scene_id);
        $sidebar = (new Sidebar())->sceneExams($scene);
        //dd($sidebar);
        return View( 'scene.show.type'.$scene->scene_type_id,
            [   'sidebar' => $sidebar,
                'scene' => $scene,
                'user' => ['exam' => 0, 'scene' => 0, 'order' => 0],
                'action' => 'IGNORE',
                'next' => "/exam/$exam_id/scene/0", //  . $this->nextSceneId($exam_id, $scene_id) . "/show"
            ]);
    }

    /**
     * Show the form for creating a new scene.
     * TODO
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * POST
     *
     * Store a newly created scene in storage.
     * TODO
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     * todo: load scene questions and answers first (eager loading)
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($exam_id, $scene_id) {
        $scene = Scene::findOrFail($scene_id);
        return View('scene.edit.type'.$scene->scene_type_id, 
            [   'sidebar' => [],
                'scene' => $scene,
                'lastpage' => URL::previous()
            ]);
    }

    /**
     * POST
     *
     * Update the specified resource in storage.
     * todo: load scene questions and answers first (eager loading)
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $exam_id, $scene_id) {
        $scene = Scene::findOrFail($scene_id);
        $data = $request->scene;
        switch ($scene->scene_type_id) {
            case 1:
                $scene->head = $data['head'];
        }
        $scene->save();

        //- todo: save question & answers

        //- return to last page
        $back = $request->lastpage ?? url("/scene/$id/show");
        return redirect($back);
    }

    /**
     * Soft-delete the specified scene.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function kill($exam_id, $scene_id) {
        $scene = Scene::findOrFail($scene_id);
        $scene->delete();
    }

    //--------

    /**
     * get a scene with its question(s) and answers
     */
    public function getFullScene($scene_id) {
        $scene = Scene::where('id', '=', $scene_id)->with('questions','questions.answers')->firstOrFail();
        if ($scene->scene_type_id == 2) {
            $this->setFirstLastQuestions($scene);
        }
        return $scene;
    }

    /**
     * let the accordion know what's what
     * todo when the questions' orders are set in db
     *
     * @param  Scene  $scene
     */
    private function setFirstLastQuestions(Scene $scene) {
        $last = count($scene->questions); // $scene->question_count
        $n = 1;
        foreach($scene->questions as &$question) {
            $question->is_first = ($n == 1) ? true : false;
            $question->is_last = ($n == $last) ? true : false;
            $question->order = $n;
            $n += 1;
        }
    }
/*
    private function nextSceneId($exam_id, $scene_id) {
        $nextScene = DB::table('exam_scene')
            ->select('scene_id')
            ->where('exam_id','=',$exam_id)
            ->where('scene_id','>',$scene_id)
            ->orderBy('scene_id')
            ->first();
        if (empty($nextScene)) {
            $nextScene = DB::table('exam_scene')
                ->select('scene_id')
                ->where('exam_id','=',$exam_id)
                ->orderBy('scene_id')
                ->first();
        }
        return (empty($nextScene)) ? 0 : $nextScene->id;
    }
*/
}

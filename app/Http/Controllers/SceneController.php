<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Scene as Scene;
use App\Models\SceneType;
use App\Models\QuestionType;
use App\Models\Exam;
use App\Helpers\Helper as Helper;
use App\Helpers\Sidebar as Sidebar;
use DB;
use Illuminate\Support\Facades\URL;
use Illuminate\Pagination\Paginator;
use App\Classes\PraxScene;
use App\Http\Requests\NewSceneRequest;

class SceneController extends Controller
{
    public function __construct() {
        $this->middleware('auth');
        $this->middleware('args2session')->only('index');
        $this->middleware('exam_owner')->only('edit','update','destroy');
    }

    /**
     * Display a listing of the exam scenes.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, $exam_id) {
        $args = $this->getIndexArgs($request);
        $args['exam'] = Exam::findOrFail($exam_id);

        /* note: the whereRaw below is a workaround for this grouped 'where' does not seem to work with paginate
              ->where( function($q) use($lf) {
                $q->where('head', 'LIKE', "'$lf'")->
                orWhere('text', 'LIKE', "'$lf'")->
                orWhere('instructions', 'LIKE', "'$lf'");
                })
            DB::enableQueryLog();
            dd(DB::getQueryLog());
        */

        $sqlfilter = Helper::likeFilter($args['filter']);
        $args['scenes'] = $args['exam']->scenes()
                ->whereRaw("(`scenes`.`head` LIKE '$sqlfilter' or `scenes`.`text` LIKE '$sqlfilter' or `scenes`.`instructions` LIKE '$sqlfilter')")
                ->orderBy($args['sortby'], $args['direction'])
                ->paginate($args['paginate']);
        $args['sidebar'] = (new Sidebar)->sbarSceneIndex($args['exam']);
        return view('scene.index', $args);
    }

    /**
     * @param Request $request
     * @return array
     */
    private function getIndexArgs(Request $request) {
        //- code for 'args2session'
        $page_base = str_replace('/','.',$request->path());
        $this->registerPaginator($request,$page_base);
        $paginate = $request->session()->get('paginate', 10);
        $filter = $request->session()->get($page_base.'.filter', "");
        $direction = $request->session()->get($page_base.'.direction', 'asc');
        $sortby = $request->session()->get($page_base.'.sortby', 'id');
        if (!in_array($sortby,['id','text','question_count','head'])) $sortby = 'id';
        return compact('paginate','filter','sortby','direction');
    }

    /**
     * Show the scene. Admin mode, action IGNORE.
     *
     * @param  int  $exam_id
     * @param  int  $scene_id
     * @return \Illuminate\Http\Response
     */
    public function show($exam_id, $scene_id) {
        $scene = $this->getFullScene($scene_id);
        $praxscene = (new PraxScene())->setAdminSceneData($scene);
        return View( 'scene.type' . $scene->scene_type_id . '.show',
            [   'sidebar' => (new Sidebar())->sceneShow($scene),
                'pagehead' => 'Scene ' . $this->getSceneOrderOfTotal($exam_id, $scene_id),
                'praxscene' => $praxscene,
                'useraction' => 'IGNORE',
                'exam_id' => $exam_id,
            ]);
    }

    /**
     * Show the form for creating a new scene.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($exam_id) {
        $exam = Exam::findOrFail($exam_id);
        $scene_types = SceneType::select('id','name')->pluck('name','id');
        return View( 'scene.create',
            [   'sidebar' => (new Sidebar())->sceneCreate($exam),
                'exam_id' => $exam_id,
                'scene_types' => $scene_types,
            ]);
    }

    /**
     * POST
     * Store a newly created scene in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(NewSceneRequest $request, $exam_id) {
        //dd($request->all());
        if ($exam_id != $request->get('exam_id')) {
            // ??
        }
        $scene = Scene::create($request->only('exam_id','head','scene_type_id'));

        return redirect("/exam/$exam_id/scene/{$scene->id}/edit");
/*
        $exam = Exam::findOrFail($exam_id);
        $question_types = QuestionType::select('id','name')->pluck('name','id');
        return View( "scene.type{$scene->scene_type_id}.edit",
            [   'sidebar' => (new Sidebar())->sceneCreate($exam),
                'exam_id' => $exam_id,
                'question_types' => $question_types,
            ]);
*/
    }

    /**
     * Show the form for editing the specified resource.
     * todo: load scene questions and answers first (eager loading)
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit( $exam_id, $scene_id) {
        $scene = $this->getFullScene($scene_id);
        $scene_types = SceneType::select('id','name')->pluck('name','id');
        $question_types = QuestionType::select('id','name')->pluck('name','id');
        return View('scene.type' . $scene->scene_type_id . '.edit',
            [   'sidebar' => (new Sidebar)->sceneEdit($scene),
                'pagehead' => 'Edit Scene ' . $this->getSceneOrderOfTotal($exam_id, $scene_id),
                'scene' => $scene,
                'scene_types' => $scene_types,
                'question_types' => $question_types,
            ]);
    }
    
    /**
     * POST
     * Update the scene.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update( NewSceneRequest $request, $exam_id, $scene_id) {
        if ($exam_id != $request->get('exam_id')) {
            // ??
        }
        $scene = Scene::findOrFail($scene_id);
        $scene->update($request->only('head','scene_type_id'));
        if ($request->has('save_show')) {
            return redirect(url("/exam/$exam_id/scene/$scene_id/show"));
        } elseif ($request->has('save_stay')) {
            return redirect(url("/exam/$exam_id/scene/$scene_id/edit"));
        } else {
            // ??
        }
    }

    /**
     * Soft-delete the specified scene.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($exam_id, $scene_id) {
        $scene = Scene::findOrFail($scene_id);
        $scene->delete();
        return redirect("/exam/$exam_id/scene");
    }

    //--------

    /**
     * get a scene with its question(s) and answers
     */
    public function getFullScene($scene_id) {
        $scene = Scene::where('id', '=', $scene_id)->with('exam','sceneType','questions','questions.answers')->firstOrFail();
        if ($scene->scene_type_id == 2) {
            $scene->setQuestionsOrder();
        }
        return $scene;
    }

    /**
     * Show the next question of this exam
     * 
     * @param int $exam_id
     * @param int $scene_id
     * @return Response
     */
    public function nextScene($exam_id, $scene_id) {
        $nextScene = $this->getNextScene($exam_id, $scene_id);
        if (empty($nextScene)) {
            return redirect("/exam/$exam_id/scene");
        } else {
            return redirect("/exam/$exam_id/scene/{$nextScene->id}");
        }
    }

    /**
     * Edit the next question of this exam
     *
     * @param $exam_id
     * @param $scene_id
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function editNextScene($exam_id, $scene_id) {
        $nextScene = $this->getNextScene($exam_id, $scene_id);
        if (empty($nextScene)) {
            return redirect("/exam/$exam_id/scene");
        } else {
            return redirect("/exam/$exam_id/scene/{$nextScene->id}/edit");
        }
    }

    /**
     * @param $exam_id
     * @param $scene_id
     * @return mixed
     */
    private function getNextScene($exam_id, $scene_id) {
        $nextScene = Scene::select('id')
            ->where('exam_id', '=', $exam_id)
            ->where('id','>',$scene_id)
            ->orderBy('id')
            ->first();
        if (empty($nextScene)) {
            $nextScene = Scene::select('id')
                ->where('exam_id', '=', $exam_id)
                ->orderBy('id')
                ->first();
        }
        return $nextScene;
    }

    /**
     * Get the Scene order index
     *
     * @param $exam_id
     * @param $scene_id
     * @return string
     */
    private function getSceneOrderOfTotal($exam_id, $scene_id) {
        $scenes = Scene::select('id')
            ->where('exam_id', '=', $exam_id)
            ->orderBy('id')
            ->get();
        $idlist = array_flip($scenes->pluck('id')->all());
        return sprintf("%d of %d", $idlist[$scene_id]+1, $scenes->count());
    }

}

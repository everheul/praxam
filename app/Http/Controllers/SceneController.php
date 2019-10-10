<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Scene as Scene;
use App\Models\Exam;
use App\Helpers\Helper as Helper;
use App\Helpers\Sidebar as Sidebar;
use DB;
use Illuminate\Support\Facades\URL;
use Illuminate\Pagination\Paginator;
use App\Classes\PraxScene;

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
        //- code for 'args2session'
        $page_base = str_replace('/','.',$request->path());
        $this->registerPaginator($request,$page_base);
        $paginate = $request->session()->get('paginate', 10);
        $filter = $request->session()->get($page_base.'.filter', "");
        $direction = $request->session()->get($page_base.'.direction', 'asc');
        $sortby = $request->session()->get($page_base.'.sortby', 'id');
        if (!in_array($sortby,['id','created_at','scene_type_id','head'])) $sortby = 'id';

        $exam = Exam::findOrFail($exam_id);
        $lf = Helper::likeFilter($filter);

        /* note: the whereRaw below is a workaround for this grouped 'where' does not seem to work with paginate
              ->where( function($q) use($lf) {
                $q->where('head', 'LIKE', "'$lf'")->
                orWhere('text', 'LIKE', "'$lf'")->
                orWhere('instructions', 'LIKE', "'$lf'");
                })

            DB::enableQueryLog();
            dd(DB::getQueryLog());
        */
        $scenes = $exam->scenes()
                ->whereRaw("(`scenes`.`head` LIKE '$lf' or `scenes`.`text` LIKE '$lf' or `scenes`.`instructions` LIKE '$lf')")
                ->orderBy($sortby, $direction)
                ->paginate($paginate);

        $sidebar = (new Sidebar)->sbarSceneIndex($exam);

        return view('scene.index', compact('exam','scenes','paginate','filter','sortby','direction','sidebar'));
    }

    /**
     * Show the scene. Admin mode, action IGNORE.
     * todo: show all fields normally, but with edit icons, to favor editting of single fields.
     *
     * @param  int  $exam_id
     * @param  int  $scene_id
     * @return \Illuminate\Http\Response
     */
    public function show($exam_id, $scene_id) {
        $scene = $this->getFullScene($scene_id);
        $praxscene = new PraxScene($scene);
        $sidebar = (new Sidebar())->sceneExams($scene);
        return View( 'scene.type' . $scene->scene_type_id . '.show',
            [   'sidebar' => $sidebar,
                'praxscene' => $praxscene,
                'useraction' => 'IGNORE',
                'exam_id' => $exam_id,
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
        $scene = $this->getFullScene($scene_id);
        
        return View('scene.type' . $scene->scene_type_id . '.edit',
            [   'sidebar' => [],
                'exam_id' => $exam_id,
                'scene' => $scene,
                'lastpage' => URL::previous(),
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
        $scene = Scene::where('id', '=', $scene_id)->with('exam','questions','questions.answers')->firstOrFail();
        if ($scene->scene_type_id == 2) {
            $scene->setQuestionsOrder();
        }
        return $scene;
    }
    
    /**
     * Redirect to the next question of this exam
     * 
     * @param Request $request
     * @param int $exam_id
     * @param int $scene_id
     * @return Response
     */
    public function nextScene(Request $request, $exam_id, $scene_id) {
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
        if (empty($nextScene)) {
            return redirect("/exam/$exam_id/scene");
        } else {
            return redirect("/exam/$exam_id/scene/{$nextScene->id}");
        }
    }
    
}

<?php

/*  SceneController
 */

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
use App\Http\Requests\NewQuestionOrderRequest;
use File;

class SceneController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('args2session')->only('index');
        $this->middleware('check_exam_route');
        $this->middleware('exam_owner')->only('edit', 'update', 'destroy', 'order');
    }

    /**
     * Display a listing of the exam scenes.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, $exam_id)
    {
        $args = $this->getIndexArgs($request);
        $args['exam'] = Exam::findOrFail($exam_id);
        $sql_filter = Helper::likeFilter($args['filter']);

        /* note: whereRaw() below is a workaround, for this grouped 'where' does not seem to work with paginate:
          ->where( function($q) use($lf) {
                $q->where('head', 'LIKE', "'$lf'")->
                orWhere('text', 'LIKE', "'$lf'")->
                orWhere('instructions', 'LIKE', "'$lf'");
            })
        */

        //DB::enableQueryLog();
        $args['scenes'] = $args['exam']->scenes()
            ->whereRaw("(`scenes`.`head` LIKE '$sql_filter' or `scenes`.`text` LIKE '$sql_filter' or `scenes`.`instructions` LIKE '$sql_filter')")
            ->orderBy($args['sortby'], $args['direction'])
            ->paginate($args['paginate']);
        //dd(DB::getQueryLog());

        $args['sidebar'] = (new Sidebar)->sbarSceneIndex($args['exam']);
        return view('scene.index', $args);
    }

    /**
     * @param Request $request
     * @return array
     */
    private function getIndexArgs(Request $request)
    {
        //- code for 'args2session'
        $page_base = str_replace('/', '.', $request->path());
        $this->registerPaginator($request, $page_base);
        $paginate = $request->session()->get('paginate', 10);
        $filter = $request->session()->get($page_base . '.filter', "%");
        $direction = $request->session()->get($page_base . '.direction', 'asc');
        $sortby = $request->session()->get($page_base . '.sortby', 'id');
        if (!in_array($sortby, ['id', 'text', 'question_count', 'head'])) $sortby = 'id';
        return compact('paginate', 'filter', 'sortby', 'direction');
    }

    /**
     * Preview the scene. Admin mode.
     *
     * @param  int $exam_id
     * @param  int $scene_id
     * @return \Illuminate\Http\Response
     */
    public function show($exam_id, $scene_id)
    {
        $scene = Scene::findOrFail($scene_id);
        return (new SceneMgr($scene))->preview();
/*

        $scene = Scene::where('id', '=', $scene_id)->with('exam', 'sceneType', 'questions', 'questions.answers')->firstOrFail();
        $scene->setQuestionsOrder(); //- todo
        $praxscene = (new PraxScene())->setAdminSceneData($scene);
        return View('scene.type' . $scene->scene_type_id . '.show',
            ['sidebar' => (new Sidebar())->sbarSceneShow($scene),
                'pagehead' => 'Scene ' . $this->getSceneOrderOfTotal($exam_id, $scene_id),
                'praxscene' => $praxscene,
                'useraction' => 'IGNORE',
                'exam_id' => $exam_id,
            ]);
*/
    }

    /**
     * Show the form for creating a new scene.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($exam_id)
    {
        $exam = Exam::findOrFail($exam_id);
        $scene_types = SceneType::select('id', 'name')->pluck('name', 'id');
        return View('scene.create',
            ['sidebar' => (new Sidebar())->sbarSceneCreate($exam),
                'exam_id' => $exam_id,
                'scene_types' => $scene_types,
                'scene' => null,
            ]);
    }

    /**
     * POST
     * Store a newly created scene in storage.
     *
     * @param  NewSceneRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(NewSceneRequest $request, $exam_id)
    {
        if ($exam_id != $request->get('exam_id')) {
            //todo: log this.
            abort(400, 'Unexpected form contents.');
        }
        $scene = Scene::create($request->only('exam_id', 'head', 'scene_type_id'));
        //- always go to edit after create, to view the type2 fields &| add questions
        return redirect("/exam/$exam_id/scene/{$scene->id}/edit");
    }

    /**
     * @param NewQuestionOrderRequest $request
     * @param $exam_id
     * @param $scene_id
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function order(NewQuestionOrderRequest $request, $exam_id, $scene_id)
    {

        // todo: check id's

        $scene = Scene::where('id', '=', $scene_id)
            ->with('questions')
            ->firstOrFail();

        $qlist = $request->get('questions');
        if (!empty($qlist) && is_array($qlist)) {
            foreach ($qlist as $question_id => $order) {
                $question = $scene->questions->firstWhere('id', $question_id);
                if (!empty($question)) {
                    $question->order = $order;
                    $question->save();
                }
            }
        } else dd($request);

        return redirect("/exam/$exam_id/scene/{$scene_id}/edit");
    }

    /**
     * Show the form for editing the specified scene.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($exam_id, $scene_id)
    {
        $scene = Scene::where('id', $scene_id)
            ->with('exam', 'questions')
            ->firstOrFail();
        $scene_types = SceneType::select('id', 'name')->pluck('name', 'id');
        return View('scene.type' . $scene->scene_type_id . '.edit',
            ['sidebar' => (new Sidebar)->sbarSceneEdit($scene),
                'pagehead' => 'Edit Scene ' . $this->getSceneOrderOfTotal($exam_id, $scene_id),
                'scene' => $scene,
                'scene_types' => $scene_types,
            ]);
    }

    /**
     * POST
     * Update the scene.
     *
     * @param  NewSceneRequest $request
     * @param  int $exam_id
     * @param  int $scene_id
     * @return \Illuminate\Http\Response
     */
    public function update(NewSceneRequest $request, $exam_id, $scene_id)
    {
        //dd($request);
        if (($exam_id != $request->get('exam_id')) || ($scene_id != $request->get('scene_id'))) {
            // todo
            abort(400, 'Unexpected form contents.');
        }
        $scene = Scene::where('id', '=', $scene_id)
            ->with('exam')
            ->firstOrFail();
        $data = $request->getData();
        $this->handleUploadImage($scene, $data, $request);
        $scene->fill($data);

        if ($scene->is_public) {
            // user wants to publish, or had it published: check validity:
            $errmsg = $scene->validityCheck();
        }
        //dd($errmsg,$scene);
        $scene->save();
        $scene->exam->countScenes();

        if (!empty($errmsg)) {
            return back()->withErrors(['is_public' => $errmsg]);
        } elseif ($request->has('save_show')) {
            return redirect(url("/exam/$exam_id/scene/$scene_id/show"));
        } elseif ($request->has('save_stay')) {
            return redirect(url("/exam/$exam_id/scene/$scene_id/edit"));
        } elseif ($request->has('save_next')) {
            return redirect(url("/exam/$exam_id/scene/$scene_id/next/edit"));
        } else {
            //todo: log this.
            abort(400, 'Unexpected form contents.');
        }
    }

    /**
     * @param array $data
     * @param NewSceneRequest $request
     */
    private function handleUploadImage(Scene $scene, Array &$data, NewSceneRequest $request)
    {
        if ($request->hasFile('newimage')) {
            $image = $request->file('newimage');
            if ($image->isValid()) {
                $name = time() . '_' . $image->getClientOriginalName();
                $image->move(public_path('/storage/images/'), $name);
                $data['image'] = '/storage/images/' . $name;
                // check for previous uploaded image, and delete it.
                if (!empty($scene->image)) {
                    File::delete(public_path($scene->image));
                }
            }
        }
    }

    /**
     * Soft-delete the specified scene.
     * todo! Prevent deletion of scenes that are used in a test!
     * Offer to 'un-public' to stop usage from now.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($exam_id, $scene_id)
    {
        $scene = Scene::where('id', '=', $scene_id)
            ->with('exam')//- todo: delete questions/answers too?
            ->firstOrFail();
        $scene->delete();
        $scene->exam->countScenes();
        return redirect("/exam/$exam_id/scene");
    }

    /**
     * Show the next question of this exam
     *
     * @param int $exam_id
     * @param int $scene_id
     * @return Response
     */
    public function nextScene($exam_id, $scene_id)
    {
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
    public function editNextScene($exam_id, $scene_id)
    {
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
    private function getNextScene($exam_id, $scene_id)
    {
        $nextScene = Scene::select('id')
            ->where('exam_id', '=', $exam_id)
            ->where('id', '>', $scene_id)
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
     * TODO: useless overhead! instead fill and use scenes.order, together with exams.scene_count
     *
     * @param $exam_id
     * @param $scene_id
     * @return string
     */
    private function getSceneOrderOfTotal($exam_id, $scene_id)
    {
        $scenes = Scene::select('id')
            ->where('exam_id', '=', $exam_id)
            ->orderBy('id')
            ->get();
        $idlist = array_flip($scenes->pluck('id')->all());
        return sprintf("%d of %d", $idlist[$scene_id] + 1, $scenes->count());
    }
}
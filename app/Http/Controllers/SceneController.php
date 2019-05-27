<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Scene as Scene;
use App\Models\User;
use App\Helpers\Sidebar as Sidebar;
use DB;
use Illuminate\Support\Facades\URL;

class SceneController extends Controller
{
    public function __construct(Request $request) {
        $this->middleware(['auth','admin']);
    }

    /**
     * Display a listing of the exam scenes.
     * TODO
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Scene::all();
    }

    /**
     * Show the scene. Admin mode, action IGNORE.
     * todo: load scene questions and answers first (eager loading)
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($exam_id, $scene_id) {
        $scene = Scene::findOrFail($scene_id);
        $sidebar = (new Sidebar())->sceneExams($scene);
        //dd($sidebar);
        return View( 'scene.show.type'.$scene->scene_type_id,
            [   'sidebar' => $sidebar,
                'scene' => $scene,
                'user' => ['exam' => 0, 'scene' => 0],
                'action' => 'IGNORE',
                'next' => "/scene/" . $scene->nextSceneId() . "/show"
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

}

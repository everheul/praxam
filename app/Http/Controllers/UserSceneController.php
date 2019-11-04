<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\UserExam;
use App\Models\Scene;
use App\Models\UserScene;
use App\Helpers\Sidebar;
use App\Classes\PraxScene;
use App\Classes\PraxQuestion;
use App\Classes\PraxAnswer;
use App\Classes\PraxExam;

class UserSceneController extends Controller
{
    // used to check user-exam ownership once
    private $user_checked;

    public function __construct() {
        $this->middleware('auth');
        $this->middleware('userexam_owner');
    }

    /**
     * Display a list of the scenes of this prax?
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        dd(" -= TODO =- ");
    }

    /**
     * Display the scene as part of a test.
     * The (user)exam and scenes are also loaded to fill the sidebar.
     *
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $userexam_id, $s_order, $q_order = 1) {

        $praxexam = (new PraxExam())->loadUserExamData($userexam_id);
        $praxscene = $praxexam->praxscenes->where('userscene.order', '=', $s_order)->first();
        $sidebar = (new SideBar)->practiceExam($praxexam, $s_order);
        
        return View('scene.type' . $praxscene->scene->scene_type_id . '.show',
            [   'sidebar' => $sidebar,
                'praxscene' => $praxscene,
                'useraction' => 'ANSWER',
                'active_question' => $q_order - 1,
            ]);
    }
}

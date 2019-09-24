<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Exam;
use App\Helpers\Sidebar;
use Illuminate\Support\Facades\Auth;
use App\Models\UserQuestion;
use App\Models\UserExam;


class HomeController extends Controller
{
    public function __construct() {
        $this->middleware('auth');
    }


    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index() {
        $user = Auth::user();
        if (!empty($user)) {
            $sidebar = (new Sidebar)->examOverview();
            $working = UserExam::where('user_id','=',$user->id)->whereNull('finished_at')->with('exam')->with('userscenes')->get();
            //dd($working);
            $hystory = UserExam::where('user_id','=',$user->id)->whereNotNull('finished_at')->with('exam')->get();
            return view('home',
                [   'sidebar' => $sidebar,
                    'working' => $working,
                    'hystory' => $hystory
                ]);
        }
    }
}

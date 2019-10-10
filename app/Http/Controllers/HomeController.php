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
     * Show the users tests and exams.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index() {
        $user = Auth::user();
        if (!empty($user)) {
            $sidebar = (new Sidebar)->sbarHomeIndex();
            $working = UserExam::where('user_id','=',$user->id)->whereNull('finished_at')->with('exam')->with('userscenes')->get();
            $hystory = UserExam::where('user_id','=',$user->id)->whereNotNull('finished_at')->with('exam')->get();
            $exams = Exam::where('created_by','=',$user->id)->get();
            return view('home', compact('sidebar','working','hystory','exams'));
        }
    }

}

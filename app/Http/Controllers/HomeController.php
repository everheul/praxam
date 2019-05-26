<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Exam;
use App\Helpers\Sidebar;

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
    public function index()
    {
        $sidebar = (new Sidebar)->examOverview();
        $exams = Exam::all()->toArray();
        return view('home',['sidebar' => $sidebar, 'exams' => $exams]);
    }
}

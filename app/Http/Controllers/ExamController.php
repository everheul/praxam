<?php

namespace App\Http\Controllers;

use App\Models\Exam;
use Illuminate\Http\Request as Request;
use App\Helpers\Sidebar;
use App\Models\User as User;
use Illuminate\Support\Facades\Auth;

class ExamController extends Controller
{

    public function __construct() {
        //$this->middleware('auth');
    }

    /**
     * Display a brief info of all exams.
     * TODO: Add 'New' button for admins.
     *
     * @return \\Illuminate\Http\Response
     */
    public function index() {
        return View('exam.index',
            ['sidebar' => (new Sidebar)->examOverview(),
                'exams' => Exam::get(['id','name','head','intro','image'])
        ]);
    }

    /**
     * Display all the info of the specified exam.
     * TODO: Add 'Edit' and 'Delete' buttons for admins.
     *
     * @param  int $id
     * @return \\Illuminate\Http\Response
     */
    public function show($exam_id) {
        $exam = Exam::findOrFail($exam_id);
        return View('exam.show',
            ['sidebar' => (new Sidebar)->examOverview($exam),
                'exam' => $exam ]
        );
    }

    /**
     * Show the form for creating a new exam.
     * TODO
     *
     * @return \\Illuminate\Http\Response
     */
    public function create() {
        dd(" -= TODO =- ");
    }

    /**
     * POST
     *
     * Store a newly created exam.
     * TODO
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request) {
        dd($request);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($exam_id) {
        $user = Auth::user();
        if (!empty($user) && $user->isAdmin()) {
            $exam = Exam::findOrFail($exam_id);
            return View('exam.edit',
                [   'sidebar' => (new Sidebar)->examEdit($exam),
                    'exam' => $exam ]
            );
        } else {
            //?
        }
        dd(" -= TODO =- ");
    }

    /**
     * POST
     *
     * Update the exam.
     * TODO: image test
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $exam_id) {
        $user = Auth::user();
        if (!empty($user) && $user->isAdmin()) {
            $exam = Exam::findOrFail($exam_id);
            $exam->fill($request->all());
            $image = $request->file('newimage');
            if (!empty($image)) {
                $image->store('public/images');
                $exam->image = $image->getFilename();
            }
            $exam->update();
        }
        return redirect(url("/exam/$id/show"));
    }

    /**
     * Soft-delete the exam.
     *
     * @param  int $id
     * @return \\Illuminate\Http\Response
     */
    public function kill($exam_id) {
        $exam = Exam::findOrFail($exam_id);
        $exam->delete();
        //$exam->deleted_at = \Carbon\Carbon::now();
        //$exam->update();
        return redirect(url("/exam"));
    }
}

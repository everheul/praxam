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
        $this->middleware('auth');
        $this->middleware('exam_owner')->only('edit','update','destroy');
    }

    /**
     * Display a brief info of all exams.
     * TODO: Add 'New' button.
     *
     * @return \\Illuminate\Http\Response
     */
    public function index() {
        return View('exam.index',
            ['sidebar' => (new Sidebar)->sbarExamIndex(),
                'exams' => Exam::get(['id','name','head','intro','image'])
        ]);
    }

    /**
     * Display all the info of the specified exam.
     * TODO: Add 'Edit' and 'Delete' buttons.
     *
     * @param  int $id
     * @return \\Illuminate\Http\Response
     */
    public function show($exam_id) {
        $exam = Exam::findOrFail($exam_id);
        return View('exam.show',
               ['sidebar' => (new Sidebar)->sbarExamShow($exam),
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
        // todo: validate
        dd(" -= TODO =- ");
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $exam_id
     * @return \Illuminate\Http\Response
     */
    public function edit($exam_id) {
        $exam = Exam::findOrFail($exam_id);
        return View('exam.edit',
            [   'sidebar' => (new Sidebar)->examEdit($exam),
                'exam' => $exam ]
        );
    }

    /**
     * POST
     *
     * Update the exam.
     * TODO: image test
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int $exam_id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $exam_id) {
        // todo: validate
        $exam = Exam::findOrFail($exam_id);
        $exam->fill($request->all());
        $image = $request->file('newimage');
        if (!empty($image)) {
            $image->store('public/images');
            $exam->image = $image->getFilename();
        }
        $exam->update();
        return redirect(url("/exam/$exam_id/show"));
    }

    /**
     * Soft-delete the exam.
     *
     * @param  int $exam_id
     * @return \\Illuminate\Http\Response
     */
    public function destroy($exam_id) {
        $exam = Exam::findOrFail($exam_id);
        $exam->delete();
        return redirect(url("/exam"));
    }

    /**
     * Jump to the next (or first) scene of this exam
     *
     * @param  int $exam_id
     * @param  int $scene_id
     * @return \\Illuminate\Http\Response
     */
    public function nextScene($exam_id, $scene_id = 0) {

        $scene = Scene::where('exam_id', $exam_id)
            ->where('id','>',$scene_id)
            ->orderBy('id')
            ->select('id')
            ->first();
        if (empty($scene)) {
            $scene = Scene::where('exam_id', $exam_id)
                ->orderBy('id')
                ->select('id')
                ->first();

        }
        if (empty($scene)) {
            //- no scenes found..
            return $this->show($exam_id);
        }
        return redirect(url("/exam/$exam_id/scene/{$scene->id}" ));
    }

}

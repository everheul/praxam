<?php

namespace App\Http\Controllers;

use App\Models\Exam;
use Illuminate\Http\Request as Request;
use App\Helpers\Sidebar;
use App\Models\User as User;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\NewExamRequest;

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
     *
     * @return \\Illuminate\Http\Response
     */
    public function create() {
        return View('exam.create',
            [   'sidebar' => (new Sidebar)->sbarNoExam(),
                'exam' => null
            ]
        );
    }

    /**
     * POST
     *
     * Store a newly created exam.
     * TODO
     *
     * @param  NewExamRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(NewExamRequest $request) {
        $data = $request->getData();
        $data['created_by'] = $request->user()->id;
        if ($request->hasFile('newimage')) {
            $image = $request->file('newimage');
            if ($image->isValid()) {
                // todo: check for previous uploaded image, and delete it.
                $name = time() . '_' . $image->getClientOriginalName();
                $image->move(public_path('/storage/images/'), $name);
                $data['image'] = '/storage/images/' . $name;
            }
        }

        //dd($data, $request);
        $exam = Exam::create($data);
        return redirect(url("/exam/{$exam->id}/edit"));
    }

    /**
     * Show the form for editing the Exam.
     *
     * @param  int $exam_id
     * @return \Illuminate\Http\Response
     */
    public function edit($exam_id) {
        $exam = Exam::findOrFail($exam_id);
        return View('exam.edit',
            [   'sidebar' => (new Sidebar)->sbarExamEdit($exam),
                'exam' => $exam ]
        );
    }

    /**
     * POST
     *
     * Update the exam.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int $exam_id
     * @return \Illuminate\Http\Response
     */
    public function update(NewExamRequest $request, $exam_id) {
        $data = $request->getData();
        if ($request->hasFile('newimage')) {
            $image = $request->file('newimage');
            if ($image->isValid()) {
                $name = time() . '_' . $image->getClientOriginalName();
                $image->move(public_path('/storage/images/'), $name);
                $data['image'] = '/storage/images/' . $name;
            }
        }

        $exam = Exam::findOrFail($exam_id);
        $exam->fill($data);
        $exam->update();

        if ($request->has('save_show')) {
            return redirect(url("/exam/{$exam->id}/show"));
        } elseif ($request->has('save_stay')) {
            return redirect(url("/exam/{$exam->id}/edit"));
        } else {
            // ??
        }
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
    
    /**
     * 
     */
    public function validateAll() {
        $exams = Exam::with('scenes','scenes.questions','scenes.questions.answers')->get();
        $valid_count = 0;
        foreach($exams as $exam) {
            if ($exam->isValid()) {
                $valid_count++;
            }
        }
        dd("Valid exams found: $valid_count");
    }

}

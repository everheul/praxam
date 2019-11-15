<?php
/**
 *  QuestionsController
 *  2019-09-24 20:29:38
 **/

namespace App\Http\Controllers;

use App\Http\Requests\NewAnswerOrderRequest;
use App\Models\Question;
use App\Models\QuestionType;
use App\Models\Scene;
use App\Helpers\Sidebar as Sidebar;
use App\Http\Requests\NewQuestionRequest;
use App\Classes\PraxScene;
use Exception;
use DB;

class QuestionController extends Controller
{
    /**
     * Create a new controller instance
     *  and register the middleware needed.
     */
	public function __construct() {
	    $this->middleware('auth'); // ?
            $this->middleware('exam_owner');
	}

    /**
     * @param  int  $exam_id
     * @param  int  $scene_id
     * @return Illuminate\View\View
     */
    public function create($exam_id, $scene_id) {
        $scene = Scene::where('id', '=', $scene_id)->with('exam')->firstOrFail();
        $question = null;
        $question_types = QuestionType::select('id','name')->pluck('name','id');
        $sidebar = (new Sidebar())->sbarQuestionCreate($scene);
        return view('question.create', compact('scene','question_types','sidebar','question'));
    }

    /**
     * @param  int  $exam_id
     * @param  int  $scene_id
     * @param  int  $question_id
     * @return Illuminate\View\View
     */
    public function edit($exam_id, $scene_id, $question_id) {
        //$scene = Scene::where('id', '=', $scene_id)->with('exam')->firstOrFail();
        $question = Question::where('id', '=', $question_id)->with('answers','scene','scene.exam')->firstOrFail();
        //dd($question);
        $question_types = QuestionType::orderBy('id')->pluck('name','id');
        $sidebar = (new Sidebar())->sbarQuestionEdit($question);
        return view("question.edit", compact('question','question_types','sidebar')); // type{$question->question_type_id}
    }

    /**
     * show the whole scene (like scene.show), but with this question active
     * todo: find-and-show any changes made since the test was done
     *
     * @param  int  $exam_id
     * @param  int  $scene_id
     * @param  int  $question_id
     * @return Illuminate\View\View
     */
    public function show($exam_id, $scene_id, $question_id) {
        $scene = Scene::where('id', '=', $scene_id)->with('exam','sceneType','questions','questions.answers')->firstOrFail();
        $scene->setQuestionsOrder(); //- todo
        $praxscene = (new PraxScene())->setAdminSceneData($scene);
        $question_order = $praxscene->questionOrder($question_id);
        $sidebar = (new Sidebar())->sbarSceneShow($scene); //- todo: edit question
        $useraction = 'IGNORE';
        return View('scene.type' . $praxscene->scene->scene_type_id . '.show', compact('praxscene','sidebar','useraction','question_order'));
    }

    /** POST
     * todo: check exam/scene id's (post & route)
     * todo: test access
     *
     * @param App\Http\Requests\NewQuestionRequest $request
     * @param  int  $exam_id
     * @param  int  $scene_id
     * @return Illuminate\Http\RedirectResponse | Illuminate\Routing\Redirector
     */
    public function store(NewQuestionRequest $request, $exam_id, $scene_id) {
        //dd($request);
        $scene = Scene::where('id', '=', $scene_id)->firstOrFail();
        $data = $request->getData();
        $data['order'] = $scene->question_count + 1;

        $question = Question::create($data);
        $this->calcQuestionCount($scene);

        return redirect()->route( 'exam.scene.question.edit', ['exam_id' => $exam_id, 'scene_id' => $scene_id, 'question_id' => $question->id] );
/*
        try {
            $question = Question::create($data);
            //- continue editting, to create the answers:
            return redirect()->route( 'exam.scene.question.edit', ['exam_id' => $exam_id, 'scene_id' => $scene_id, 'question_id' => $question->id] );
        } catch (Exception $exception) {
            return back()->withInput()
                ->withErrors(['unexpected_error' => 'Unexpected error occurred while trying to process your request.']);
        } */
    }

    /** POST
     *
     * @param App\Http\Requests\QuestionsFormRequest $request
     * @param  int  $exam_id
     * @param  int  $scene_id
     * @param  int  $question_id
     * @return Illuminate\Http\RedirectResponse | Illuminate\Routing\Redirector
     */
    public function update(NewQuestionRequest $request, $exam_id, $scene_id, $question_id) {
        //dd($request);

        $data = $request->getData();
        // todo: check exam/scene id's

        $question = Question::where('id',$question_id)->with('answers')->firstOrFail();
        $question->fill($data);
        //$question->validityCheck();  done by scene
        $question->save();

        if ($request->has('save_show')) {
            //return redirect(url("/exam/$exam_id/scene/$scene_id/question/{$question->id}/show"));
            return redirect()->route( 'exam.scene.question.show', ['exam_id' => $exam_id, 'scene_id' => $scene_id, 'question_id' => $question->id] );
        } elseif ($request->has('save_stay')) {
            return redirect()->route( 'exam.scene.question.edit', ['exam_id' => $exam_id, 'scene_id' => $scene_id, 'question_id' => $question->id] );
        } elseif ($request->has('save_next')) {
            return redirect()->route( 'exam.scene.question.next.edit', ['exam_id' => $exam_id, 'scene_id' => $scene_id, 'question_id' => $question->id] );
        } else {
            // ??
        }
    }

    /**
     * Remove the specified question from the storage.
     * todo! Prevent deletion of questions that were used in a test!
     * Offer to 'un-public' to stop usage from now.
     * todo: validate?
     *
     * @param int $id
     *
     * @return Illuminate\Http\RedirectResponse | Illuminate\Routing\Redirector
     */
    public function destroy($exam_id, $scene_id, $question_id) {
        $question = Question::where('id',$question_id)->with('scene')->firstOrFail();
        $question->delete();
        $this->calcQuestionCount($question->scene);
        return redirect()->route('exam.scene.edit', ['exam_id' => $exam_id, 'scene_id' => $scene_id])
                    ->with('success_message', 'Question was successfully deleted.');
    }

    /**
     * @param $exam_id
     * @param $scene_id
     * @param $question_id
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function nextQuestion($exam_id, $scene_id, $question_id) {
        $nextId = $this->getNextQuestion($exam_id, $scene_id, $question_id);
        if (empty($nextId)) {
            return redirect("/exam/$exam_id/scene/$scene_id/edit");
        } else {
            return redirect("/exam/$exam_id/scene/$scene_id/question/$nextId");
        }
    }

    /**
     * @param $exam_id
     * @param $scene_id
     * @param $question_id
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function editNextQuestion($exam_id, $scene_id, $question_id) {
        $nextId = $this->getNextQuestion($exam_id, $scene_id, $question_id);
        if (empty($nextId)) {
            return redirect("/exam/$exam_id/scene/$scene_id/edit");
        } else {
            return redirect("/exam/$exam_id/scene/$scene_id/question/$nextId/edit");
        }
    }

    /**
     *  select q2.id from questions q2
        join questions q1 on (q1.scene_id = q2.scene_id AND q2.order > q1.order)
        where q1.id = {$question_id}
        order by q2.order limit 1
     *
     * @param $exam_id
     * @param $scene_id
     * @param $question_id
     * @return int | null
     */
    private function getNextQuestion($exam_id, $scene_id, $question_id) {
        //DB::enableQueryLog();
        $id = DB::table('questions as q2')
            ->join('questions as q1', function ($q) {
                $q->on('q1.scene_id', '=', 'q2.scene_id')
                    ->on('q2.order', '>', 'q1.order');
            })
            ->where('q1.id','=',$question_id)
            ->orderBy('q2.order')
            ->limit(1)
            ->value('q2.id');
        ///dd($id, DB::getQueryLog());
        return $id;
    }


    /**
     * @param $exam_id
     * @param $scene_id
     * @param $question_id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function answers($exam_id, $scene_id, $question_id) {
        $question = Question::where('id',$question_id)->with('scene','scene.exam','answers')->firstOrFail();
        $sidebar = (new Sidebar())->sbarQuestionAnswers($question);
        $correct = $question->answers->filter( function($answer, $key) {
            return $answer->is_correct;
        })->sortBy('correct_order');
        return view("question.answers", compact('question','sidebar','correct'));
    }

    /**
     * @param NewAnswerOrderRequest $request
     * @param $exam_id
     * @param $scene_id
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function order(NewAnswerOrderRequest $request, $exam_id, $scene_id, $question_id) {

        //dd($request);

        // todo: check id's

        $question = Question::where('id', '=', $question_id)
            ->with('answers')
            ->firstOrFail();
        
        $alist = $request->get('answers');
        if(!empty($alist) && is_array($alist)) {
            foreach($alist as $answer_label => $order) {
                $answer_id = substr($answer_label,7); // answer_<ID>
                $answer = $question->answers->firstWhere('id', $answer_id );
                if (!empty($answer)) {
                    $answer->order = $order;
                    $answer->is_correct = 0;
                    $answer->correct_order = 0;
                } else {
                    // todo: what?
                    dd($alist);
                }
            }
        }
        
        $alist = $request->get('correct');
        if(!empty($alist) && is_array($alist)) {
            foreach($alist as $answer_label => $order) {
                $answer_id = substr($answer_label,12); // // copy_answer_<ID>
                $answer = $question->answers->firstWhere('id', $answer_id );
                if (!empty($answer)) {
                    $answer->is_correct = 1;
                    $answer->correct_order = $order;
                } else {
                    // todo: what?
                    dd($alist);
                }
            }
        }
        
        //- save all answers:
        foreach($question->answers as $answer) {
            $answer->save();
        }

        if ($request->has('save_show')) {
            return redirect()->route( 'exam.scene.question.show', ['exam_id' => $exam_id, 'scene_id' => $scene_id, 'question_id' => $question->id] );
        } elseif ($request->has('save_stay')) {
            return redirect()->route( 'exam.scene.question.answers', ['exam_id' => $exam_id, 'scene_id' => $scene_id, 'question_id' => $question->id] );
        } else {
            // ??
        }

    }

    /**
     * called on store and destroy.
     * todo: should be an event?
     *
     * @param Scene $scene
     */
    private function calcQuestionCount(Scene $scene) {
        $scene->question_count = DB::table('questions')
            ->where('scene_id',$scene->id)
            ->whereNull('deleted_at')
            ->count();
        $scene->save();
    }

}

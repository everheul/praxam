<?php
/**
 *  QuestionsController
 *  2019-09-24 20:29:38
 **/

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\QuestionFormRequest;
use App\Models\Question;
use App\Models\QuestionType;
use App\Models\Scene;
use Exception;
use App\Helpers\Sidebar as Sidebar;
use App\Http\Requests\NewQuestionRequest;
use App\Classes\PraxQuestion;
use App\Classes\PraxScene;

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
        $sidebar = (new Sidebar())->questionCreate($scene);
        return view('question.create', compact('scene','question_types','sidebar','question'));
    }

    /**
     * @param  int  $exam_id
     * @param  int  $scene_id
     * @param  int  $question_id
     * @return Illuminate\View\View
     */
    public function edit($exam_id, $scene_id, $question_id) {
        $scene = Scene::where('id', '=', $scene_id)->with('exam')->firstOrFail();
        $question = Question::where('id', '=', $question_id)->with('answers')->firstOrFail();
        //dd($scene,$question);
        $question_types = QuestionType::orderBy('id')->pluck('name','id');
        $sidebar = (new Sidebar())->questionEdit($scene);
        return view("question..edit", compact('scene','question','question_types','sidebar')); // type{$question->question_type_id}
    }

    /**
     * show the whole scene, with this question active
     *
     * @param  int  $exam_id
     * @param  int  $scene_id
     * @param  int  $question_id
     * @return Illuminate\View\View
     */
    public function show($exam_id, $scene_id, $question_id) {
        $scene = Scene::where('id', '=', $scene_id)->with('exam','sceneType','questions','questions.answers')->firstOrFail();
        $praxscene = (new PraxScene())->setAdminSceneData($scene);
        $question_order = $praxscene->questionOrder($question_id);
        $sidebar = (new Sidebar())->sceneShow($scene); //- todo: edit question
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

        $scene = Scene::where('id', '=', $scene_id)->firstOrFail();
        $data = $request->getData();
        $data['order'] = $scene->question_count + 1;

        try {
            $question = Question::create($data);
            //- continue editting, to create the answers:
            return redirect()->route( 'exam.scene.question.edit', ['exam_id' => $exam_id, 'scene_id' => $scene_id, 'question_id' => $question->id] );
        } catch (Exception $exception) {
            return back()->withInput()
                ->withErrors(['unexpected_error' => 'Unexpected error occurred while trying to process your request.']);
        }
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

        $data = $request->getData();
        //dd($data);
        // todo: check exam/scene id's

        $question = Question::findOrFail($question_id);
        $question->update($data);

        if ($request->has('save_show')) {
            //return redirect(url("/exam/$exam_id/scene/$scene_id/question/{$question->id}/show"));
            return redirect()->route( 'exam.scene.question.show', ['exam_id' => $exam_id, 'scene_id' => $scene_id, 'question_id' => $question->id] );
        } elseif ($request->has('save_stay')) {
            return redirect()->route( 'exam.scene.question.edit', ['exam_id' => $exam_id, 'scene_id' => $scene_id, 'question_id' => $question->id] );
        } else {
            // ??
        }
    }

    /**
     * Remove the specified question from the storage.
     *
     * @param int $id
     *
     * @return Illuminate\Http\RedirectResponse | Illuminate\Routing\Redirector
     */
    public function destroy($exam_id, $scene_id, $question_id) {
        $question = Question::where('id',$question_id)->with('scene')->firstOrFail();
        $question->scene->question_count -= 1;
        $question->scene->update();
        $question->delete();
        return redirect()->route('exam.scene.edit', ['exam_id' => $exam_id, 'scene_id' => $scene_id])
                    ->with('success_message', 'Question was successfully deleted.');
     }



}

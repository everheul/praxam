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

class QuestionController extends Controller
{
    /**
     * Create a new controller instance
     *  and register the middleware needed.
     */
	public function __construct()
	{
	    $this->middleware('auth');
        $this->middleware('args2session')->only('index');
	}

    /**
     * Display a listing of the questions.
     *
     * @param Illuminate\Http\Request $request
     *
     * @return Illuminate\View\View
     */
    public function index(Request $request)
    {
        $page_base = str_replace('/','.',$request->path());

        // make Paginator find its settings in session:
        $this->registerPaginator($request,$page_base);

        // get the global 'paginate' from session root:
        $paginate = $request->session()->get('paginate', 10);

        // get those as local (page) args:
        $filter = $request->session()->get($page_base.'.filter', "%");
        $direction = $request->session()->get($page_base.'.direction', 'asc');
        $sortby = $request->session()->get($page_base.'.sortby', 'id');
        //- check the sortby value:
        if (!in_array($sortby,['question_type_id','order','head'])) $sortby = 'id';

        $questions = Question::
                    orderBy($sortby, $direction)->
                    with('questiontype')->
                    paginate($paginate);

        $data = compact('questions','paginate','filter','sortby','direction');

        return view('crest.questions.index', $data);
    }

    /**
     * Show the form for creating a new question.
     *
     * @return Illuminate\View\View
     */
    public function create()
    {
        $scenes = Scene::orderBy('head')->pluck('head','id');
		$questionTypes = QuestionType::orderBy('name')->pluck('name','id');
        
        return view('crest.questions.create', compact('scenes','questionTypes'));
    }

    /**
     * Store a new question in the storage.
     *
     * @param App\Http\Requests\QuestionsFormRequest $request
     *
     * @return Illuminate\Http\RedirectResponse | Illuminate\Routing\Redirector
     */
    public function store(QuestionsFormRequest $request)
    {
        try {
            
            $data = $request->getData();
            
            Question::create($data);

            return redirect()->route('crest.questions.index')
                ->with('success_message', 'Question was successfully added.');
        } catch (Exception $exception) {

            return back()->withInput()
                ->withErrors(['unexpected_error' => 'Unexpected error occurred while trying to process your request.']);
        }
    }

    /**
     * Display the specified question.
     *
     * @param int $id
     *
     * @return Illuminate\View\View
     */
    public function show($id)
    {
        $question = Question::
                     with('scene','questiontype')->findOrFail($id);

        return view('crest.questions.show', compact('question'));
    }

    /**
     * Show the form for editing the specified question.
     *
     * @param int $id
     *
     * @return Illuminate\View\View
     */
    public function edit($id)
    {
        $question = Question::findOrFail($id);
        $scenes = Scene::orderBy('head')->pluck('head','id');
		$questionTypes = QuestionType::orderBy('name')->pluck('name','id');

        return view('crest.questions.edit', compact('question','scenes','questionTypes'));
    }

    /**
     * Update the specified question in the storage.
     *
     * @param int $id
     * @param App\Http\Requests\QuestionsFormRequest $request
     *
     * @return Illuminate\Http\RedirectResponse | Illuminate\Routing\Redirector
     */
    public function update($id, QuestionsFormRequest $request)
    {
        try {
            
            $data = $request->getData();
            
            $question = Question::findOrFail($id);
            $question->update($data);

            return redirect()->route('crest.questions.index')
                ->with('success_message', 'Question was successfully updated.');
        } catch (Exception $exception) {

            return back()->withInput()
                ->withErrors(['unexpected_error' => 'Unexpected error occurred while trying to process your request.']);
        }        
    }

    /**
     * Remove the specified question from the storage.
     *
     * @param int $id
     *
     * @return Illuminate\Http\RedirectResponse | Illuminate\Routing\Redirector
     */
    public function destroy($id)
    {
        try {
            $question = Question::findOrFail($id);
            
            $question->delete();

            return redirect()->route('crest.questions.index')
                ->with('success_message', 'Question was successfully deleted.');
        } catch (Exception $exception) {

            return back()->withInput()
                ->withErrors(['unexpected_error' => 'Unexpected error occurred while trying to process your request.']);
        }
    }



}

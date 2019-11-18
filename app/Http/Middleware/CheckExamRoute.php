<?php

namespace App\Http\Middleware;

use Closure;
use DB;
use App\Models\Exam;
use App\Models\Scene;
use App\Models\Question;
use App\Models\Answer;

class CheckExamRoute
{
    /**
     * Check the relation (and post variables, if any) of the exam/scene/question/answer id's.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if ($request->is('*exam/*')) {
            $exam_id = $request->route('exam_id');
            if (!empty($exam_id)) {
                if ($request->has('exam_id') && ($request->get('exam_id') != $exam_id)) {
                    Log::critical("Middleware\\CheckExamRoute: Altered request - 'exam_id' in route does not match form input! user_id: " . $request->user()->id .
                        ", route: " . $request->route()->uri() .
                        ", request: " . var_export($request->all(), true));
                    abort(400, 'Unexpected request. SQ');
                }

                if ($request->is('*/scene/*')) {
                    $scene_id = $request->route('scene_id');
                    if (!empty($scene_id)) {
                        if ($request->has('scene_id') && ($request->get('scene_id') != $scene_id)) {
                            Log::critical("Middleware\\CheckExamRoute: Altered request - 'scene_id' in route does not match form input! user_id: " . $request->user()->id .
                                ", route: " . $request->route()->uri() .
                                ", request: " . var_export($request->all(), true));
                            abort(400, 'Unexpected request. UE');
                        }

                        if ($request->is('*/question/*')) {
                            $question_id = $request->route('question_id');
                            if (!empty($question_id)) {
                                if ($request->has('question_id') && ($request->get('question_id') != $question_id)) {
                                    Log::critical("Middleware\\CheckExamRoute: Altered request - 'question_id' in route does not match form input! user_id: " . $request->user()->id .
                                        ", route: " . $request->route()->uri() .
                                        ", request: " . var_export($request->all(), true));
                                    abort(400, 'Unexpected request. LR');
                                }

                                if ($request->is('*/answer/*')) {
                                    $answer_id = $request->route('answer_id');
                                    if (!empty($answer_id)) {
                                        if ($request->has('answer_id') && ($request->get('answer_id') != $answer_id)) {
                                            Log::critical("Middleware\\CheckExamRoute: Altered request - 'answer_id' in route does not match form input! user_id: " . $request->user()->id .
                                                ", route: " . $request->route()->uri() .
                                                ", request: " . var_export($request->all(), true));
                                            abort(400, 'Unexpected request. GT');
                                        }

                                        if (!DB::table('answers')
                                            ->join('questions','questions.id','=','answers.question_id')
                                            ->join('scenes','scenes.id','=','questions.scene_id')
                                            ->join('exams','exams.id','=','scenes.exam_id')
                                            ->where('answers.id', $answer_id)
                                            ->where('questions.id', $question_id)
                                            ->where('scenes.id', $scene_id)
                                            ->where('exams.id', $exam_id)
                                            ->exists()
                                        ) {
                                            Log::critical("Middleware\\CheckExamRoute: Route relationship does not exist. user: " . $request->user()->id .
                                                ", route: " . $request->route()->uri() .
                                                ", request: " . var_export($request->all(), true));
                                            abort(400, 'Unexpected request. XA');
                                        }
                                    }
                                    return $next($request);
                                }

                                if (!DB::table('questions')
                                    ->join('scenes','scenes.id','=','questions.scene_id')
                                    ->join('exams','exams.id','=','scenes.exam_id')
                                    ->where('questions.id', $question_id)
                                    ->where('scenes.id', $scene_id)
                                    ->where('exams.id', $exam_id)
                                    ->exists()
                                ) {
                                    Log::critical("Middleware\\CheckExamRoute: Route relationship does not exist. user: " . $request->user()->id .
                                        ", route: " . $request->route()->uri() .
                                        ", request: " . var_export($request->all(), true));
                                    abort(400, 'Unexpected request. LP');
                                }
                            }
                            return $next($request);
                        }

                        if (!DB::table('scenes')
                            ->join('exams','exams.id','=','scenes.exam_id')
                            ->where('scenes.id', $scene_id)
                            ->where('exams.id', $exam_id)
                            ->exists()
                        ) {
                            Log::critical("Middleware\\CheckExamRoute: Route relationship does not exist. user: " . $request->user()->id .
                                ", route: " . $request->route()->uri() .
                                ", request: " . var_export($request->all(), true));
                            abort(400, 'Unexpected request. GA');
                        }
                    }
                }
            }
        }
        return $next($request);
    }

}

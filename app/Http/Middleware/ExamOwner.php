<?php

namespace App\Http\Middleware;

use Closure;

class ExamOwner
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next) {
        if (!$request->user()->isAdmin()) {
            if ($request->is('exam/*')) {
                $exam_id = $request->route('exam_id');
                if (!empty($exam_id)) {
                    if (!Exam::where('id', '=', $exam_id)
                        ->where('created_by', '=', $request->user()->id)
                        ->exists()) {
                        // todo: log this
                        return redirect('/home');
                    }
                }
            }
        }
        return $next($request);
    }
}

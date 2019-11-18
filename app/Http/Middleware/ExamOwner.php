<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\Exam;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Log;

class ExamOwner
{
    /**
     * Make sure the user owns this exam - or is admin.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next) {
        if (!$request->user()->isAdmin()) {
            if ($request->is('exam/*')) {
                $exam_id = $request->route('exam_id');
                if (empty($exam_id)) {
                    Log::critical("Middleware\\ExamOwner: No 'exam_id' in route! user_id: " . $request->user()->id . ", request: " . var_export($request->all(),true));
                    abort(400, "Exam id not given.");
                }
                if (!Exam::where('id', '=', $exam_id)
                            ->where('created_by', '=', $request->user()->id)
                            ->exists()) {
                    Log::critical("Middleware\\ExamOwner: User tried access to others exam! user_id: " . $request->user()->id .
                        ", route: " . $request->route()->uri() .
                        ", request: " . var_export($request->all(),true));
                    abort(400, 'Unexpected request, incorrect behaviour.');
                }
            }
        }
        return $next($request);
    }
}

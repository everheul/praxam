<?php

namespace App\Http\Middleware;

use Closure;

class UserExamOwner
{
    /**
     * redirect a user to HOME when the userexam->user_id doesn't match user->id
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next) {
        if (!$request->user()->isAdmin()) {
            if ($request->is('prax/*')) {
                $userexam_id = $request->route('prax_id');
                if (!empty($userexam_id)) {
                    if (!UserExam::where('id','=',$userexam_id)
                        ->where('user_id','=',$request->user()->id)
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

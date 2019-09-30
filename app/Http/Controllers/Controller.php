<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Pagination\Paginator;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    /**
     * this function co-exists with ArgsToSession middleware
     *
     * @param $request
     * @param null $base
     */
    protected function registerPaginator($request, $base = null) {
        if (empty($base)) {
            $base = str_replace('/','.',$request->path());
        }
        // paginator normally gets the page from the url; change that to session:
        Paginator::currentPageResolver(function () use ($request, $base) {
            return $request->session()->get($base.'.page', 1);
        });
    }

}

<?php

/**
 * Ekke, 22-08-2019
 *
 * This will move the GET arguments in $session_args[] to session, with the route base and argument name as key,
 *  ..and will then redirect to the same url, without the moved arguments.
 */

namespace App\Http\Middleware;

use Closure;
use \Illuminate\Http\Request;

class ArgsToSession
{
    Const IS_GLOBAL = true;
    const IS_LOCAL = false;

    // those arguments will be moved to session
    // todo: move to config? handleArgument()?
    protected $session_args = [
        'paginate' => self::IS_GLOBAL,
        'filter' => self::IS_LOCAL,
        'sortby' => self::IS_LOCAL,
        'direction' => self::IS_LOCAL,
        'page' => self::IS_LOCAL,
    ];
    
    protected $unknown_args = [];
    
    protected $reset_page = false;

    protected $must_redirect = false;
    
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next, $guard = null)
    {
        $input = $this->getInput($request);
        if (!empty($input)) {
            $base = str_replace('/','.',trim($request->path(),'/'));
            if (!empty($base)) {
                $this->argsToSession($request, $input, $base);
                if ($this->must_redirect) {
                    return redirect(url(url()->current(),$this->unknown_args));
                }
            }
        }
        return $next($request);
    }

    private function getInput($request) {
        if ($request->isMethod('get')) {
            return $request->all();
        }
        return false;
    }

    private function argsToSession($request, $input, $base) {
        foreach($input as $key => $value) {
            if (array_key_exists($key, $this->session_args)) {
                $this->must_redirect = true;
                $this->handleArgument($request, $base, $key, $value);
            } else {
                $this->unknown_args[$key] = $value;
            }
        }
        if ($this->reset_page) {
            $request->session()->put($base . '.page', 1);
        }
    }

    private function handleArgument($request, $base, $key, $value) {
        switch ($key) {
            case 'page':
                $value = $this->positiveInt($value);
                break;
            case 'paginate':
                $value = $this->positiveInt($value);
                $this->reset_page = true;
                break;
            case 'filter':
                $value = $this->testFilter($value);
                $this->reset_page = true;
                break;
            case 'sortby':
                //- test sortby value in controller!!
                $this->reset_page = true;
                break;
            case 'direction':
                $value = (strtolower($value) === 'desc') ? 'desc' : 'asc';
                $this->reset_page = true;
                break;
        }
        $this->toSession($request, $base, $key, $value);
    }

    private function toSession($request, $base, $key, $value) {
        if (empty($value)) {
            // an empty value will delete the session key
            if ($this->session_args[$key] === self::IS_GLOBAL) {
                $request->session()->forget($key);
            } else {
                $request->session()->forget("$base.$key");
            }
        } else {
            if ($this->session_args[$key] === self::IS_GLOBAL) {
                $request->session()->put($key, $value);
            } else {
                $request->session()->put("$base.$key", $value);
            }
        }
    }

    private function positiveInt($s) {
        $i = intval($s);
        return $i < 1 ? 1 : $i;
    }

    private function testFilter($s) {
        $s = trim($s);
        $s = stripslashes($s);
        $s = htmlspecialchars($s);
        //- replace stars for procent (dos to sql)
        $s = str_replace('*','%',$s);
        return $s;
    }

}

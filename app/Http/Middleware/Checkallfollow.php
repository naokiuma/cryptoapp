<?php

namespace App\Http\Middleware;

use Closure;
use Session;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
class Checkallfollow
{
  /**
  * Handle an incoming request.
  *
  * @param  \Illuminate\Http\Request  $request
  * @param  \Closure  $next
  * @return mixed
  */
  public function handle($request, Closure $next)
  {
    Log::debug("ミドルウェアを通ります。");
    /*

    勝手に変わることはないから不要。
      if(Auth::check()){
        $autofollow_check = Auth::user()->autofollow;
        Log::debug("autofollow_checkの状態".$autofollow_check);
        Log::debug("1だとオートフォローはon、0だとオートフォローはoff");
        Log::debug("セッション：autofollowを調整します");

        if($autofollow_check == 1){
          Session::put('autofollow', true);//セッションにautofollo実施中である旨を入れる。
        }else{
          Session::forget('autofollow');
        }
      }
      */
      return $next($request);
  }
}

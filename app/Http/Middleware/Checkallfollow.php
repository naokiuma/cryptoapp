<?php

namespace App\Http\Middleware;

use Closure;
use Session;
use Illuminate\Support\Facades\Log;
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
      //■■■まとめてフォローをできるかどうかの判定（15分ごとの判定）。■■■
      //$autofollow_readyが1ならフォローできない、0ならできる。
      //まずはセッションにtoday_follow_timeがあるかどうかを確認。ある場合はフラグを1にする。
      //
      if(Session::get('today_follow_time')){
        $autofollow_ready = 1;//1の場合はオートフォロー不可能。
        $nowtime = date("Y/m/d H:i:s");//現在時刻
        $last_followtime = Session::get('today_follow_time');
        Log::debug('最後にオートフォローした時間です'.$last_followtime);

        //今の時間と、前回のフォロー時間をタイムスタンプに入れる。
        $timeStamp1 = strtotime($nowtime);
        $timeStamp2 = strtotime($last_followtime);
        //タイムスタンプの差を計算
        $difSeconds = $timeStamp1 - $timeStamp2;
        Log::debug('$timeStamp1と2の差です。'.$difSeconds);
        //分を計算
        $difMinutes = ($difSeconds - ($difSeconds % 60)) / 60;
        $diffTime = $difMinutes % 60;
        Log::debug($diffTime."分経過しています。");

            if($diffTime > 14){//15分以上経過しているならセッションを削除しオートフォロー可能な状態に。
              Log::debug("前回のまとめてフォローから15分経過しました！タイムをリセットします。");
              Session::forget('today_follow_time');
              $autofollow_ready = 0;//オートフォロー可能な状態
            }else{
              Log::debug("まだ15分経過していません");
            }

        }else{
          $autofollow_ready = 0;//オートフォロー可能な状態
        }
        
      Log::debug("ーーーーーーーーーーーーーーーーーーーーーーー");
      Log::debug("まとめてフォローの状態です。".$autofollow_ready);
      Log::debug("ーーーーーーーーーーーーーーーーーーーーーーー");
      Log::debug("0だと実施可能です。１だと実施できません。");

        return $next($request);
    }
}

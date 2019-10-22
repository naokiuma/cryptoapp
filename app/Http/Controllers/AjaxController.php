<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Coin;
use Abraham\TwitterOAuth\TwitterOAuth;

class AjaxController extends Controller
{
  //ーーーーーーーーーーajaxデータ。DBから取得したcoinデータをajaxとして出力ーーーーーーーーーー
  public function coin() {
    //コインデータ一覧をDBから表示
    return Coin::all();
  }

}

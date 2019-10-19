<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Coin;
use Abraham\TwitterOAuth\TwitterOAuth;

class AjaxController extends Controller
{//ajaxデータ。DBから取得したcoinデータをajaxとして出力
  public function coin() {
    //コインデータ一覧をDBから表示
    return Coin::all();
  }

}

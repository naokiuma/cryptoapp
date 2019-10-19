<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Socialite;
use Session;
use Illuminate\Database\Eloquent\Model;
use App\Coin;
use Abraham\TwitterOAuth\TwitterOAuth;


class NewsController extends Controller
{

  //------------------ニュース一覧アクション

  public function index()
  {
    set_time_limit(120);
    $max_num = 50;
    $keywords = "仮想通貨";//検索キーワード
    $query = urlencode(mb_convert_encoding($keywords,"UTF-8", "auto"));
    $API_BASE_URL = "https://news.google.com/rss/search?ie=UTF-8&oe=UTF-8&q=".$keywords."&hl=ja&gl=JP&ceid=JP:ja";
    $items = simplexml_load_file($API_BASE_URL)->channel->item;

    //記事のタイトルとURLを取り出して配列に格納
    for ($i = 0; $i < count($items); $i++) {
      $list[$i]['title'] = mb_convert_encoding($items[$i]->title,"UTF-8", "auto");//mb_convert_encodingで文字列を変換
      $list[$i]['url'] = mb_convert_encoding($items[$i]->link,"UTF-8", "auto");
      $list[$i]['pubDate'] = mb_convert_encoding($items[$i]->pubDate,"UTF-8", "auto");
      //メディア画像がある場合はその画像を、ない場合はサービスのデフォルト画像を配置。
      /*
      if(isset($items[$i]->children('media', true)->content)){
      //記事のmedia内に画像があれば読み込む
      $list[$i]['image_url'] = (string)$items[$i]->children('media', true)->content->attributes()->url;
    }else{
    //記事のmedia内に画像がなければサンプル画像
    $list[$i]['image_url'] = "./img/hero_img.jpg";
  }
  */
  }

  //$max_num以上の記事数の場合は切り捨て
  if(count($list)>$max_num){
    for ($i = 0; $i < $max_num; $i++){
      $list_gn[$i] = $list{$i};
      $i++;
    }
  }else{
    $list_gn = $list;
  }
  return view('news/index',compact('list_gn'));
  }

}

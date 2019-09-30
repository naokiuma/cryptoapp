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
    $keywords = "仮想通貨";
    $query = urlencode(mb_convert_encoding($keywords,"UTF-8", "auto"));
    $API_BASE_URL = "https://news.google.com/rss/search?ie=UTF-8&oe=UTF-8&q=".$keywords."&hl=ja&gl=JP&ceid=JP:ja";
    $items = simplexml_load_file($API_BASE_URL)->channel->item;

    //記事のタイトルとURLを取り出して配列に格納
    for ($i = 0; $i < count($items); $i++) {
        $list[$i]['title'] = mb_convert_encoding($items[$i]->title,"UTF-8", "auto");//mb_convert_encodingで文字列を変換
        $list[$i]['url'] = mb_convert_encoding($items[$i]->link,"UTF-8", "auto");
        $list[$i]['pubDate'] = mb_convert_encoding($items[$i]->pubDate,"UTF-8", "auto");
        //メディア画像がある場合はその画像を、ない場合はサービス画像を。
        if(isset($items[$i]->children('media', true)->content)){
          //記事のmedia内に画像があれば読み込む
          $list[$i]['image_url'] = (string)$items[$i]->children('media', true)->content->attributes()->url;
        }else{
          //記事のmedia内に画像がなければサンプル画像
          $list[$i]['image_url'] = "./img/hero_img.jpg";
        }
      }

    //$max_num以上の記事数の場合は切り捨て
    if(count($list)>$max_num){
        for ($i = 0; $i < $max_num; $i++){
            $list_gn[$i] = $list{$i};
            $i++;
        }
    }else{
        $list_gn = $list;
        //Log::debug(print_r($list_gn));
        //print_r(count($list));
    }

     return view('news/index',compact('list_gn'));

  }

//------------------------------------

public function test()
//dbにインサートする処理（どこでやってもいい。あとでcronにする）→coinのdayに移しました。

{

  $config = config('services');
  $consumerKey = $config['twitter']['client_id'];
  $consumerSecret = $config['twitter']['client_secret'];
  $accessToken = $config['twitter']['access_token'];
  $accessTokenSecret = $config['twitter']['access_token_secret'];
  date_default_timezone_set('Asia/Tokyo');//https://blog.codecamp.jp/php-datetime参考
  $now_time = date("Y-m-d_H:i:s")."_JST";
  $before_day = date('Y-m-d_H:i:s', strtotime('-1 day', time()))."_JST";
  echo '<pre>'; print_r($now_time); echo '</pre>';
  echo '<pre>'; print_r($before_day); echo '</pre>';


  $oAuth = new TwitterOAuth($consumerKey, $consumerSecret, $accessToken, $accessTokenSecret);
  #検索ワード複数
  $search_key = '"ビットコイン" OR "イーサリアム" OR "イーサリアムクラシック" OR "リスク（LSK）" OR "ファクトム" OR "リップル" OR
  "ネム" OR "ライトコイン" OR "ビットコインキャッシュ" OR "モナコイン" OR "ダッシュ" OR "ジーキャッシュ" OR "モネロ" OR "オーガー"';
  // 取得オプション
  $options = array('q'=>$search_key, 'count'=>100, 'result_type' => 'recent','since' => $before_day,'until' => $now_time, );
  // 取得
  $request_loop = 10; //上限 180
  $tweet_results = array();

  for($i=0; $i<$request_loop; $i++){
      $results = $oAuth->get("search/tweets", $options);
    foreach($results->statuses as $val){
      $tweet_results[]['text'] = $val->text; //とりあえず取得したツイートを配列へ
    }
  //これ以上取得できるツイートがあるか条件分岐
  if(isset($results->search_metadata->next_results)){
    //次ページのmax_id値を取得
    $max_id = preg_replace('/.*?max_id=([\d]+)&.*/', '$1', $results->search_metadata->next_results);
    $options['max_id'] = $max_id; // あればmax_idをoptionsに追加
    }else{
    break; // なければループを抜ける
    }
  }

  $btc = $eth = $etc = $lsk = $fct = $xrp = $xem = $ltc = $bch = $mona = $dash = $zec = $xmr = $rep = 0;
  $count = count($tweet_results);//ツイート数
  echo $count . "←tweet_resultsの中身";

  for($i = 0; $i < $count; $i++){
    if(stristr($tweet_results[$i]['text'],"ビットコイン") !== false){
      $btc++;
      }
    if(stristr($tweet_results[$i]['text'],"イーサリアム") !== false){
      $eth++;
      }
    if(stristr($tweet_results[$i]['text'],"イーサリアムクラシック") !== false){
      $etc++;
      }
    if(stristr($tweet_results[$i]['text'],"リスク") !== false){
      $lsk++;
      }
    if(stristr($tweet_results[$i]['text'],"ファクトム") !== false){
      $fct++;
      }
    if(stristr($tweet_results[$i]['text'],"リップル") !== false){
      $xrp++;
      }
    if(stristr($tweet_results[$i]['text'],"ネムコイン") !== false){
      $xem++;
      }
    if(stristr($tweet_results[$i]['text'],"ライトコイン") !== false){
      $ltc++;
      }
    if(stristr($tweet_results[$i]['text'],"ビットコインキャッシュ") !== false){
      $bch++;
      }
    if(stristr($tweet_results[$i]['text'],"モナコイン") !== false){
      $mona++;
      }
    if(stristr($tweet_results[$i]['text'],"ダッシュ") !== false){
      $dash++;
      }
    if(stristr($tweet_results[$i]['text'],"ジーキャッシュ") !== false){
      $zec++;
      }
    if(stristr($tweet_results[$i]['text'],"モネロ") !== false){
      $xmr++;
      }
    if(stristr($tweet_results[$i]['text'],"オーガー") !== false){
      $rep++;
      }
  }

echo $btc . "←btcの中身";
echo $eth . "←ethの中身";
echo $etc . "←etcの中身";
echo $lsk . "←lskの中身";
echo $fct . "←fctの中身";
echo $xrp . "←xrpの中身";
echo $xem . "←xemの中身";
echo $ltc . "←ltcの中身";
echo $bch . "←bchの中身";
echo $mona. "←monaの中身";
echo $dash. "←dashの中身";
echo $zec.  "←zecの中身";
echo $xmr.  "←xmrの中身";
echo $rep.  "←repの中身";

$coin_btc = Coin::where('id', 1)->first();
$coin_eth = Coin::where('id', 2)->first();
$coin_etc = Coin::where('id', 3)->first();
$coin_lsk = Coin::where('id', 4)->first();
$coin_fct = Coin::where('id', 5)->first();
$coin_xrp = Coin::where('id', 6)->first();
$coin_xem = Coin::where('id', 7)->first();
$coin_ltc = Coin::where('id', 8)->first();
$coin_bch = Coin::where('id', 9)->first();
$coin_mona = Coin::where('id', 10)->first();
$coin_dash = Coin::where('id', 11)->first();
$coin_zec = Coin::where('id', 12)->first();
$coin_xmr = Coin::where('id', 13)->first();
$coin_rep = Coin::where('id', 14)->first();

$coin_btc->hour = $btc;
$coin_btc->save();

$coin_eth->hour = $eth;
$coin_eth->save();

$coin_etc->hour = $etc;
$coin_etc->save();

$coin_lsk->hour = $lsk;
$coin_lsk->save();

$coin_fct->hour = $fct;
$coin_fct->save();

$coin_xrp->hour = $xrp;
$coin_xrp->save();

$coin_xem->hour = $xem;
$coin_xem->save();

$coin_ltc->hour = $ltc;
$coin_ltc->save();

$coin_bch->hour = $bch;
$coin_bch->save();

$coin_mona->hour = $mona;
$coin_mona->save();

$coin_dash->hour = $dash;
$coin_dash->save();

$coin_zec->hour = $zec;
$coin_zec->save();

$coin_xmr->hour = $xmr;
$coin_xmr->save();

$coin_rep->hour = $rep;
$coin_rep->save();

}


//取引価格取得
public static function test2(){

  $API_btc_URL = "https://coincheck.com/api/ticker";
  $btc_json = file_get_contents($API_btc_URL);
  $btc_json = mb_convert_encoding($btc_json, 'UTF8', 'ASCII,JIS,UTF-8,EUC-JP,SJIS-WIN');
  $btc = json_decode($btc_json, true);
  echo "ビットコイン最大値".$btc['high'];
  echo "<br>";
  echo "ビットコイン最低値".$btc['low'];

  $API_eth_URL = "https://api.zaif.jp/api/1/ticker/eth_jpy";
  $eth_json = file_get_contents($API_eth_URL);
  $eth_json = mb_convert_encoding($eth_json, 'UTF8', 'ASCII,JIS,UTF-8,EUC-JP,SJIS-WIN');
  $eth = json_decode($eth_json, true);
  echo "<br>";
  echo "イーサリアム最大値".$eth['high'];
  echo "<br>";
  echo "イーサリアム最低値".$eth['low'];

  $API_xem_URL = "https://api.zaif.jp/api/1/ticker/xem_jpy";
  $xem_json = file_get_contents($API_xem_URL);
  $xem_json = mb_convert_encoding($xem_json, 'UTF8', 'ASCII,JIS,UTF-8,EUC-JP,SJIS-WIN');
  $xem = json_decode($xem_json, true);
  echo "<br>";
  echo "ネム最大値".$xem['high'];
  echo "<br>";
  echo "ネム最低値".$xem['low'];

  $API_bch_URL = "https://api.zaif.jp/api/1/ticker/bch_jpy";
  $bch_json = file_get_contents($API_bch_URL);
  $bch_json = mb_convert_encoding($bch_json, 'UTF8', 'ASCII,JIS,UTF-8,EUC-JP,SJIS-WIN');
  $bch = json_decode($bch_json, true);
  echo "<br>";
  echo "ビットコインキャッシュ最大値".$bch['high'];
  echo "<br>";
  echo "ビットコインキャッシュ最低値".$bch['low'];


  $API_mona_URL = "https://api.zaif.jp/api/1/ticker/mona_jpy";
  $mona_json = file_get_contents($API_mona_URL);
  $mona_json = mb_convert_encoding($mona_json, 'UTF8', 'ASCII,JIS,UTF-8,EUC-JP,SJIS-WIN');
  $mona = json_decode($mona_json, true);
  echo "<br>";
  echo "モナコイン最大値".$mona['high'];
  echo "<br>";
  echo "モナコインキャッシュコイン".$mona['low'];


  $coin_btc = Coin::where('id', 1)->first();
  $coin_eth = Coin::where('id', 2)->first();
  $coin_xem = Coin::where('id', 7)->first();
  $coin_bch = Coin::where('id', 9)->first();
  $coin_mona = Coin::where('id', 10)->first();


  $coin_btc->high = $btc['high'];
  $coin_btc->low = $btc['low'];
  $coin_btc->save();

  $coin_eth->high = $eth['high'];
  $coin_eth->low = $eth['low'];
  $coin_eth->save();

  $coin_xem->high = $xem['high'];
  $coin_xem->low = $xem['low'];
  $coin_xem->save();

  $coin_bch->high = $bch['high'];
  $coin_bch->low = $bch['low'];
  $coin_bch->save();

  $coin_mona->high = $mona['high'];
  $coin_mona->low = $mona['low'];
  $coin_mona->save();


  return view('news/test2');

}






//参考：        $list[$i]['url'] = $items[$i]->link;
/*
    一覧画面では各Twitterアカウントのユーザ名（＠と英数字のもの）、
    アカウント名、フォロー数、フォロワー数、プロフィール、最新ツイート一件を表示する。
    [nickname] => sagaluma
    [name] => ひよこ橋
    [followers_count] => 1975
    [friends_count] => 1805
    [description] => クライミングやってる/Switch/チキン南蛮/ビール/webサービス関連業務7年くらい/プログラミングは去年初めました/html css js php/vue.js/laravel
    [status]['text'] => 最新のツイート
    [token] => 110430424-0CD5oaOGcLHcH2LtiPDmFEFuTkUHBLV0H3Qb8rYk
    [tokenSecret] => ZwnlujdVGpqOLNiCXaPi2ZP21qXrn9NuOOH48SHdLtbrz
    [created_at] => Mon Feb 01 14:29:46 +0000 2010
    [avatar] => http://pbs.twimg.com/profile_images/1160763260305465344/ndZ5tmba_normal.jpg
*/






}

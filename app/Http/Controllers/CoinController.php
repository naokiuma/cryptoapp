<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Abraham\TwitterOAuth\TwitterOAuth;
use App\Coin;

class CoinController extends Controller
{
    public function index()
    {

      return view('coin/index');
    }



    //dbに1時間のツイート数をインサートする処理（定期バッジをする）
    public function hour()
    {

      $config = config('services');
      $consumerKey = $config['twitter']['client_id'];
      $consumerSecret = $config['twitter']['client_secret'];
      $accessToken = $config['twitter']['access_token'];
      $accessTokenSecret = $config['twitter']['access_token_secret'];
      date_default_timezone_set('Asia/Tokyo');//https://blog.codecamp.jp/php-datetime参考
      $now_time = date("Y-m-d_H:i:s")."_JST";//今の時間
      $before_hour = date('Y-m-d_H:i:s', strtotime('-1 hour', time()))."_JST";//先日の時間
      echo '<pre>'; print_r($now_time); echo '</pre>';
      echo '<pre>'; print_r($before_hour); echo '</pre>';


      $oAuth = new TwitterOAuth($consumerKey, $consumerSecret, $accessToken, $accessTokenSecret);
      #検索ワード複数
      $search_key = '"ビットコイン" OR "イーサリアム" OR "イーサリアムクラシック" OR "仮想通貨リスク" OR "ファクトム" OR "リップル" OR
      "ネム" OR "ライトコイン" OR "ビットコインキャッシュ" OR "モナコイン" OR "仮想通貨ダッシュ" OR "ジーキャッシュ" OR "モネロ" OR "オーガー"';
      // 取得オプション
      $options = array('q'=>$search_key, 'count'=>100, 'result_type' => 'recent','since' => $before_hour,'until' => $now_time, );
      // 取得
      $request_loop = 30; //15分の上限 180
      $tweet_results = array();
      $results = $oAuth->get("search/tweets", $options);
      for($i=0; $i<$request_loop; $i++){
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

    return view('coin/hour');
    }



    //dbに1weekのツイート数をインサートする処理（定期バッジをする）
    public function day()
    {

      $config = config('services');
      $consumerKey = $config['twitter']['client_id'];
      $consumerSecret = $config['twitter']['client_secret'];
      $accessToken = $config['twitter']['access_token'];
      $accessTokenSecret = $config['twitter']['access_token_secret'];
      date_default_timezone_set('Asia/Tokyo');//https://blog.codecamp.jp/php-datetime参考
      $now_time = date("Y-m-d_H:i:s")."_JST";//今の時間
      $before_day = date('Y-m-d_H:i:s', strtotime('-1 day', time()))."_JST";//先日の時間
      echo '<pre>'; print_r($now_time); echo '</pre>';
      echo '<pre>'; print_r($before_day); echo '</pre>';

      //各種通貨の


      $oAuth = new TwitterOAuth($consumerKey, $consumerSecret, $accessToken, $accessTokenSecret);
      #検索ワード複数
      $search_key = '"ビットコイン" OR "イーサリアム" OR "イーサリアムクラシック" OR "仮想通貨リスク" OR "ファクトム" OR "リップル" OR
      "ネム" OR "ライトコイン" OR "ビットコインキャッシュ" OR "モナコイン" OR "仮想通貨ダッシュ" OR "ジーキャッシュ" OR "モネロ" OR "オーガー"';
      // 取得オプション
      $options = array('q'=>$search_key, 'count'=>100, 'result_type' => 'recent','since' => $before_day,'until' => $now_time, );
      // 取得
      $request_loop = 10; //上限 180
      $tweet_results = array();
      $results = $oAuth->get("search/tweets", $options);

      for($i=0; $i<$request_loop; $i++){
        foreach($results->statuses as $val){
          $tweet_results[]['text'] = $val->text; //取得したツイートを配列へ
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

    $coin_btc->day = $btc;
    $coin_btc->save();

    $coin_eth->day = $eth;
    $coin_eth->save();

    $coin_etc->day = $etc;
    $coin_etc->save();

    $coin_lsk->day = $lsk;
    $coin_lsk->save();

    $coin_fct->day = $fct;
    $coin_fct->save();

    $coin_xrp->day = $xrp;
    $coin_xrp->save();

    $coin_xem->day = $xem;
    $coin_xem->save();

    $coin_ltc->day = $ltc;
    $coin_ltc->save();

    $coin_bch->day = $bch;
    $coin_bch->save();

    $coin_mona->day = $mona;
    $coin_mona->save();

    $coin_dash->day = $dash;
    $coin_dash->save();

    $coin_zec->day = $zec;
    $coin_zec->save();

    $coin_xmr->day = $xmr;
    $coin_xmr->save();

    $coin_rep->day = $rep;
    $coin_rep->save();

    return view('coin/day');
    }



    public function week()
    //dbに1日のツイート数をインサートする処理（cronにする）
    {

      $config = config('services');
      $consumerKey = $config['twitter']['client_id'];
      $consumerSecret = $config['twitter']['client_secret'];
      $accessToken = $config['twitter']['access_token'];
      $accessTokenSecret = $config['twitter']['access_token_secret'];
      date_default_timezone_set('Asia/Tokyo');//https://blog.codecamp.jp/php-datetime参考
      $now_time = date("Y-m-d_H:i:s")."_JST";//今の時間
      $before_week = date('Y-m-d_H:i:s', strtotime('-7 day', time()))."_JST";//先日の時間
      echo '<pre>'; print_r($now_time); echo '</pre>';
      echo '<pre>'; print_r($before_week); echo '</pre>';


      $oAuth = new TwitterOAuth($consumerKey, $consumerSecret, $accessToken, $accessTokenSecret);
      #検索ワード複数
      $search_key = '"ビットコイン" OR "イーサリアム" OR "イーサリアムクラシック" OR "仮想通貨リスク" OR "ファクトム" OR "リップル" OR
      "ネム" OR "ライトコイン" OR "ビットコインキャッシュ" OR "モナコイン" OR "仮想通貨ダッシュ" OR "ジーキャッシュ" OR "モネロ" OR "オーガー"';
      // 取得オプション
      $options = array('q'=>$search_key, 'count'=>100, 'result_type' => 'recent','since' => $before_week,'until' => $now_time, );
      // 取得
      $request_loop = 30; //上限 180
      $tweet_results = array();
      $results = $oAuth->get("search/tweets", $options);

      for($i=0; $i<$request_loop; $i++){
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

    $coin_btc->week = $btc;
    $coin_btc->save();

    $coin_eth->week = $eth;
    $coin_eth->save();

    $coin_etc->week = $etc;
    $coin_etc->save();

    $coin_lsk->week = $lsk;
    $coin_lsk->save();

    $coin_fct->week = $fct;
    $coin_fct->save();

    $coin_xrp->week = $xrp;
    $coin_xrp->save();

    $coin_xem->week = $xem;
    $coin_xem->save();

    $coin_ltc->week = $ltc;
    $coin_ltc->save();

    $coin_bch->week = $bch;
    $coin_bch->save();

    $coin_mona->week = $mona;
    $coin_mona->save();

    $coin_dash->week = $dash;
    $coin_dash->save();

    $coin_zec->week = $zec;
    $coin_zec->save();

    $coin_xmr->week = $xmr;
    $coin_xmr->save();

    $coin_rep->week = $rep;
    $coin_rep->save();

    return view('coin/week');
    }


}

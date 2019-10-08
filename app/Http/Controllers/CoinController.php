<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Abraham\TwitterOAuth\TwitterOAuth;
use App\Updatetime;
use App\Coin;

//通貨トレンド関連のクラス。
//indexでページを表示させ、hour/day/week/highandlowでdb上のcoinテーブルの値を更新。cronで定期更新
class CoinController extends Controller
{
    public function index()
    {
      $coinupdatedate = Updatetime::all();//全てのコインの更新日時をDBより引用
      $hour = $coinupdatedate[0]["updated_at"];//時間単位のツイートの更新日時
      $day = $coinupdatedate[1]["updated_at"];//1日単位のツイートの更新日時
      $week = $coinupdatedate[2]["updated_at"];
      $highlow = $coinupdatedate[3]["updated_at"];

      return view('coin/index',compact('hour','day','week','highlow'));
    }



    //dbに1時間のツイート数をDBインサートする処理（定期バッジをする）
    //ページにアクセスすると結果も確認可能
    public static function hour()
    {
      //ツイッター認証
      $config = config('services');
      $consumerKey = $config['twitter']['client_id'];
      $consumerSecret = $config['twitter']['client_secret'];
      $accessToken = $config['twitter']['access_token'];
      $accessTokenSecret = $config['twitter']['access_token_secret'];
      date_default_timezone_set('Asia/Tokyo');//https://blog.codecamp.jp/php-datetime参考
      $now_time = date("Y-m-d_H:i:s")."_JST";//今の時間
      $before_hour = date('Y-m-d_H:i:s', strtotime('-1 hour', time()))."_JST";//カウント開始の時間
      echo '<pre>'; print_r($now_time); echo '</pre>';
      echo '<pre>'; print_r($before_hour); echo '</pre>';


      $oAuth = new TwitterOAuth($consumerKey, $consumerSecret, $accessToken, $accessTokenSecret);
      #検索ワード複数
      $search_key ='"仮想通貨" OR "ビットコイン" OR "Btc" OR "イーサリアム" OR "Eth" OR "イーサリアムクラシック" OR "Etc" OR "仮想通貨リスク" OR "Lisk" OR
      "ファクトム" OR "Fct" OR "リップル" OR "Xrp" OR "ネム" OR "Nem " OR "ライトコイン" OR "Ltc" OR "ビットコインキャッシュ" OR "Bch" OR
      "モナコイン" OR "Mona" OR "仮想通貨ダッシュ” OR "Dash" OR "ジーキャッシュ" OR "Zec" OR "モネロ" OR "Xmr" OR "オーガー" OR "Rep"';
      // 取得オプション
      $options = array('q'=>$search_key, 'count'=>100, 'result_type' => 'recent','since' => $before_hour,'until' => $now_time, );
      // 取得
      $request_loop = 1; //15分の上限 180
      $tweet_results = array();
      $results = $oAuth->get("search/tweets", $options);


      for($i=0; $i<$request_loop; $i++){
        foreach($results->statuses as $val){
          $tweet_results[]['text'] = $val->text; //取得したツイートを配列へ積み重ねていく
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
      //  Log::debug($tweet_results);確認用


      $btc = $eth = $etc = $lsk = $fct = $xrp = $xem = $ltc = $bch = $mona = $dash = $zec = $xmr = $rep = 0;
      $count = count($tweet_results);//ツイート数

      //一致するテキストがあればカウントアップ
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


    //DB上の更新日時記録テーブルの「hour/id:1」を更新
    date_default_timezone_set('Asia/Tokyo');
    $now_time = date("Y-m-d H:i:s");//今の時間
    //Log::debug($now_time);
    $addusertime_update = Updatetime::where('id', 1)->first();//dbからデータ取得
    $data = ['updated_at' => $now_time];
    $addusertime_update->update($data);

    return view('coin/hour');
    }







    //dbに1時間のツイート数をDBインサートする処理（定期バッジをする）
    //ページにアクセスすると結果も確認可能
    public static function day()
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
      $btc = $eth = $etc = $lsk = $fct = $xrp = $xem = $ltc = $bch = $mona = $dash = $zec = $xmr = $rep = 0;

      $oAuth = new TwitterOAuth($consumerKey, $consumerSecret, $accessToken, $accessTokenSecret);
      #検索ワード複数
      $search_key = '"仮想通貨" OR "ビットコイン" OR "Btc" OR "イーサリアム" OR "Eth" OR "イーサリアムクラシック" OR "Etc" OR "仮想通貨リスク" OR "Lisk" OR
      "ファクトム" OR "Fct" OR "リップル" OR "Xrp" OR "ネム" OR "Nem " OR "ライトコイン" OR "Ltc" OR "ビットコインキャッシュ" OR "Bch" OR
      "モナコイン" OR "Mona" OR "仮想通貨ダッシュ” OR "Dash" OR "ジーキャッシュ" OR "Zec" OR "モネロ" OR "Xmr" OR "オーガー" OR "Rep"';
      // 取得オプション
      $options = array('q'=>$search_key, 'count'=>100, 'result_type' => 'recent','since' => $before_day,'until' => $now_time, );
      // 取得
      $request_loop = 24; //上限 180
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

        Log::debug($tweet_results);//確認用

      $btc = $eth = $etc = $lsk = $fct = $xrp = $xem = $ltc = $bch = $mona = $dash = $zec = $xmr = $rep = 0;
      $count = count($tweet_results);//ツイート数
      echo $count . "←tweet_resultsの中身";

      for($i = 0; $i < $count; $i++){
        if(stristr($tweet_results[$i]['text'],"ビットコイン") !== false || stristr($tweet_results[$i]['text'],"BTC") !== false){
          $btc++;
          }
        if(stristr($tweet_results[$i]['text'],"イーサリアム") !== false || stristr($tweet_results[$i]['text'],"ETH") !== false){
          $eth++;
          }
        if(stristr($tweet_results[$i]['text'],"イーサリアムクラシック") !== false || stristr($tweet_results[$i]['text'],"ETC") !== false){
          $etc++;
          }
        if(stristr($tweet_results[$i]['text'],"リスク") !== false || stristr($tweet_results[$i]['text'],"LISK") !== false){
          $lsk++;
          }
        if(stristr($tweet_results[$i]['text'],"ファクトム") !== false || stristr($tweet_results[$i]['text'],"FCT") !== false){
          $fct++;
          }
        if(stristr($tweet_results[$i]['text'],"リップル") !== false || stristr($tweet_results[$i]['text'],"XRP") !== false){
          $xrp++;
          }
        if(stristr($tweet_results[$i]['text'],"ネムコイン") !== false || stristr($tweet_results[$i]['text'],"XEM") !== false){
          $xem++;
          }
        if(stristr($tweet_results[$i]['text'],"ライトコイン") !== false || stristr($tweet_results[$i]['text'],"LTC") !== false){
          $ltc++;
          }
        if(stristr($tweet_results[$i]['text'],"ビットコインキャッシュ") !== false || stristr($tweet_results[$i]['text'],"BCH") !== false){
          $bch++;
          }
        if(stristr($tweet_results[$i]['text'],"モナコイン") !== false || stristr($tweet_results[$i]['text'],"mona") !== false){
          $mona++;
          }
        if(stristr($tweet_results[$i]['text'],"ダッシュ") !== false || stristr($tweet_results[$i]['text'],"Dash") !== false){
          $dash++;
          }
        if(stristr($tweet_results[$i]['text'],"ジーキャッシュ") !== false || stristr($tweet_results[$i]['text'],"ZEC") !== false){
          $zec++;
          }
        if(stristr($tweet_results[$i]['text'],"モネロ") !== false || stristr($tweet_results[$i]['text'],"XMR") !== false){
          $xmr++;
          }
        if(stristr($tweet_results[$i]['text'],"オーガー") !== false || stristr($tweet_results[$i]['text'],"REP") !== false){
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


    //DB上の更新日時記録テーブル「day/id:2」を更新
    date_default_timezone_set('Asia/Tokyo');
    $now_time = date("Y-m-d H:i:s");//今の時間
    //Log::debug($now_time);
    $addusertime_update = Updatetime::where('id', 2)->first();//dbからデータ取得
    $data = ['updated_at' => $now_time];
    $addusertime_update->update($data);

    return view('coin/day');
    }


    //dbに1週間のツイート数をインサートする処理（cronでの定期バッジをする）
    public static function week()
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
      $search_key = '"仮想通貨" OR "ビットコイン" OR "Btc" OR "イーサリアム" OR "Eth" OR "イーサリアムクラシック" OR "Etc" OR "仮想通貨リスク" OR "Lisk" OR
      "ファクトム" OR "Fct" OR "リップル" OR "Xrp" OR "ネム" OR "Nem " OR "ライトコイン" OR "Ltc" OR "ビットコインキャッシュ" OR "Bch" OR
      "モナコイン" OR "Mona" OR "仮想通貨ダッシュ” OR "Dash" OR "ジーキャッシュ" OR "Zec" OR "モネロ" OR "Xmr" OR "オーガー" OR "Rep"';
      // 取得オプション
      $options = array('q'=>$search_key, 'count'=>100, 'result_type' => 'recent','since' => $before_week,'until' => $now_time, );
      // 取得
      $request_loop = 100; //上限 180
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

    //DB上の更新日時記録テーブルの「week/id:3」を更新
    date_default_timezone_set('Asia/Tokyo');
    $now_time = date("Y-m-d H:i:s");//今の時間
    //Log::debug($now_time);
    $addusertime_update = Updatetime::where('id', 3)->first();//dbからデータ取得
    $data = ['updated_at' => $now_time];
    $addusertime_update->update($data);

    return view('coin/week');
    }


    //---------------coincheckや、zaifなどのパブリックAPIから取引価格取得しDBに保管
    //ページにアクセスするとまとめた情報を見ることもできる
    public static function highandlow(){

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


      //DB上の更新日時記録テーブルを更新
      date_default_timezone_set('Asia/Tokyo');
      $now_time = date("Y-m-d H:i:s");//今の時間
      //Log::debug($now_time);
      $addusertime_update = Updatetime::where('id', 4)->first();//dbからデータ取得
      $data = ['updated_at' => $now_time];
      $addusertime_update->update($data);
      return;

    }



}

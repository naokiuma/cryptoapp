<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Coin;
use Abraham\TwitterOAuth\TwitterOAuth;

class AjaxController extends Controller
{//ajaxデータ。DBから取得する
    public function coin() {
      //コインデータ一覧を表示
      return Coin::all();
      }


    public function users(){//テスト
      $config = config('services');
      $consumerKey = $config['twitter']['client_id'];	// APIキー
      $consumerSecret = $config['twitter']['client_secret'];	// APIシークレット
      $accessToken = $config['twitter']['access_token'];	// アクセストークン
      $accessTokenSecret = $config['twitter']['access_token_secret'];	// アクセストークンシークレット
      $request_url = 'https://api.twitter.com/1.1/users/search.json';	// エンドポイント
      $request_method = 'GET';

        $oAuth = new TwitterOAuth($consumerKey, $consumerSecret, $accessToken, $accessTokenSecret);
        #検索ワード複数
        $search_key = '仮想通貨';
        // 取得オプション
        $options = array('q'=>$search_key, 'count'=>20, 'lang' =>'ja','page'=>2,'entities' => false,);
        // 取得
        $request_loop = 10; //上限 180
        $tweet_results = array();
        $results = $oAuth->get("users/search", $options);
        //  print_r($results);
        //echo gettype($results);配列かオブジェクトか調べる関数。配列だった。

        //ここでjson形式にして、綺麗に見ることができる。//https://lab.syncer.jp/Tool/JSON-Viewer/

        //echo $results[0]->name."<br>";
        //var_export($results[0]->following);//var_exportはfollowing情報も出せる。

        header( "Content-Type: application/json; charset=utf-8" );//jsonデータに変換
        //jsonにする処理
        $results = json_encode($results,JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);



        print_r($results);
        return;
    }
}

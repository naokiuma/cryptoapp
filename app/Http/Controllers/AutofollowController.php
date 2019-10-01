<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB; //これ参考https://readouble.com/laravel/5.7/ja/queries.html
use Illuminate\Database\Eloquent\Model;
use App\Autofollow;
use App\User;
use Session;
use Abraham\TwitterOAuth\TwitterOAuth;

//print_r($follow_users);
//Log::debug($temp_user);

class AutofollowController extends Controller
{

/*レートリミット確認。メモ
  $oAuth = new TwitterOAuth($consumerKey, $consumerSecret, $accessToken, $accessTokenSecret);
  $limitstatus = $oAuth->get("application/rate_limit_status", ["screen_name" => $username]);
  Log::debug("リミットレートです。");
  print_r($limitstatus);//確認用
  */

//ログインユーザーのセッション情報を元にまとめる関数。
  public function twitteroauth(){
    Log::debug("セッション情報です");
    Log::debug(Session('user_token'));
    Log::debug(Session('user_tokensecret'));
    $config = config('services');
    $consumerKey = $config['twitter']['client_id'];	// APIキー
    $consumerSecret = $config['twitter']['client_secret'];	// APIシークレット
    $accessToken = (Session('user_token'));	// ログインユーザーのアクセストークン（twiitercontloreにて、セッション情報として保管）
    $accessTokenSecret = (Session('user_tokensecret'));	// ログインユーザーのアクセストークンシークレット
    $oAuth = new TwitterOAuth($consumerKey, $consumerSecret, $accessToken, $accessTokenSecret);
    return $oAuth;
  }
//------------------------------------


  //トップページ
  public function index(){

    Log::debug("処理1:DB上の前回のアクセス日と異なるかチェックします。");


      //まずは前回にフォローした日付を確認し、違う日であればリセットする。（日本時間）
      date_default_timezone_set('Asia/Tokyo');
      $today = date("Y-m-d");
      Log::debug("本日の日付".$today);
      $dbfollow_day = Auth::user()->follow_day;
      Log::debug("DB上のフォローした日".$dbfollow_day);

    //db上のフォローをした日付と本日が違う場合
      if($today !== $dbfollow_day)
      {
          Log::debug("日付が異なります。DB上のフォロー数をリセットし、日付を変更します。");
          //フォロー数をリセットし、本日に日付に更新。
          Auth::user()->follow_count = 0;
          Auth::user()->follow_day = $today;
            Auth::user()->save();
        }else{
          //db上のフォローをした日付と本日が同じ場合
          Log::debug("以前の日付と同じです。");
      }


    //次に、1日のフォロー数制限が385超えていたらフォローできないようにするフラグをonにする
    Log::debug("処理2:一日のフォロー数制限が385超えていたらフォローできないようにする");

    $follow_count = Auth::user()->follow_count;
    Log::debug("本日このサービスでフォローした数".$follow_count);
      if($follow_count > 385)
      {
        Log::debug("すでに385フォロー超えています。");
        //ここにフォローをできないようにする処理を入れる。
      }

    //情報を取得するにはSession::get
    //情報を置くにはSession::put
    //情報を消すにはSession::forget

    //まとめてフォローをできるかどうかの判定（15分ごとの判定）。
    //$autofollow_readyが1ならできない、0ならできる。
    //まずはセッションにtoday_follow_timeがあるかどうかを確認。
    if(Session::get('today_follow_time')){
      $autofollow_ready = 1;//ある場合はオートフォロー不可能。
      $nowtime = date("Y/m/d H:i:s");//現在時刻
      $last_followtime = Session::get('today_follow_time');
      Log::debug('最後にオートフォローした時間です'.$last_followtime);

      //今の時間と、前回のフォロー時間をタイムスタンプに入れる。
      $timeStamp1 = strtotime($nowtime);
      $timeStamp2 = strtotime($last_followtime);
      Log::debug('$timeStamp1です'.$timeStamp1);
      Log::debug('$timeStamp2です'.$timeStamp2);

      //タイムスタンプの差を計算
      $difSeconds = $timeStamp1 - $timeStamp2;
      Log::debug('$timeStamp1と2の差です。'.$difSeconds);

      $difMinutes = ($difSeconds - ($difSeconds % 60)) / 60;
      $diffTime = $difMinutes % 60;
      Log::debug('分の差の計算です。'.$diffTime);

          if($diffTime > 14){//15分経過しているなら
            Log::debug("前回のオートフォローから15分経過しました！タイムをリセットします。");
            Session::forget('today_follow_time');
            $autofollow_ready = 0;//オートフォロー可能な状態
          }else{
            Log::debug("まだ15分経過していません");
          }

      }else{
        $autofollow_ready = 0;//オートフォロー可能な状態
      }

    Log::debug("オートフォローの状態です。".$autofollow_ready);
    Log::debug("0だと実施可能です");



    //もしセッション情報がない場合Twitter認証をしていないため、ビュー側では下記の情報は出さない。
    //代わりにajaxでdb上のユーザーデータを表示させる。値は$temp_userに入れます。
    $oAuth = $this->twitteroauth();//関数
    $follow_users = array();
    for($i = 0; $i < 30; $i++){
      //DBからユーザーのscreen_nameのみランダムに取得し、$randomUserに詰め込む。
      $randomUser = Autofollow::inRandomOrder()->first();
      //それを$follow_usersに詰め込む
      array_push($follow_users,$randomUser->screen_name);
        }

    $follow_users = implode(",", $follow_users);//クォーテーションをつける。
    //$follow_usersはランダムにDBから取得したユーザー情報。
    //$follow_usersから取得した中で、ログインユーザーがフォローしてないユーザーの情報を取得し$lookupuserに格納。
    $lookupuser = $oAuth->get("users/lookup", ["screen_name" => "$follow_users"]);
    $temp_user = array();
    //$temp_userにスクリーンネームのみ格納。
    for($i=0; $i<12; $i++){//本来15
        if(!$lookupuser[$i]->following){
         $temp_user[] = ($lookupuser[$i]);
       }
    }

    //jsonエンコードし、ユーザー情報を取得する。
    $users_results = json_encode($temp_user,JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    //json確認用
    //header( "Content-Type: application/json; charset=utf-8" );//jsonデータに変換
    //print_r($users_results);
      //echo gettype($results);配列かオブジェクトか調べる関数。配列だった。
      //ここでjson形式にして、綺麗に見ることができる。//https://lab.syncer.jp/Tool/JSON-Viewer/
      //echo $results[0]->name."<br>";
      //var_export($results[0]->following);//var_exportはfollowing情報も出せる。
      //Log::debug($users_results);
      //Log::debug($follow_users);

    return view('autofollow/index',compact('users_results','follow_users','autofollow_ready'));
    //$follow_usersはランダムにDBから取得したユーザー情報。
    //$users_resultsは、ログインユーザーがフォローしてないユーザー一覧のスクリーンネーム。
    //autofollow_readyは、まとめてフォローを実施できるかどうかの値。1ならできない、0ならできる。
  }



//フォローアクションーーーーーーーー

  public function follow(Request $request){

    header("Access-Control-Allow-Origin: *");  //CROS
    header("Access-Control-Allow-Headers: Origin, X-Requested-With");
      $oAuth = $this->twitteroauth();


      Log::debug("リクエストの中身");  //フォローボタンを押した時に送られる中身
      Log::debug($request->data);  //フォローボタンを押した時に送られる中身
      $user_id = $request->data{"user_id"};//リクエストからidとスクリーンネームを変数に入れる
      $username = $request->data{"user_name"};
      Log::debug("フォローするユーザー情報");//フォローしたいユーザー確認用
      Log::debug($username);//フォローしたいユーザー確認用
      Log::debug($user_id);//フォローしたいユーザー確認用

      //$options = array('user_id' => $user_id);
      Log::debug("フォローします。".$username);
      $oAuth->post("friendships/create", ["screen_name" => $username]);

  return response()->json(['result' => true]);
  }



  //画面上の全ユーザーフォローアクションーーーーーーーーーーーーーーーー

  public function allfollow(Request $request){

    //Log::debug("リクエストの中身（フォロー対象のユーザー。）");  //フォローボタンを押した時に送られる中身
    Log::debug($request->allusers);
    Log::debug("オールフォローを実施します。");

    //$get_allfollow = explode(",", (array)$request->allusers->screen_name);//クォーテーションをつける。
    Log::debug("オールフォローを実施しました。");
    Log::debug("配列にしたものです。");
    //Log::debug($get_allfollow);

    $oAuth = $this->twitteroauth();
    foreach ($request->allusers as $value)
    {
    $target = $value['screen_name'];
    $oAuth->post("friendships/create", ["screen_name" => $target]);
    Log::debug($target."をフォローしました");
  }

  //オールフォローをしたら。セッション[today_follow_time]に時間を入れる。
  if(!Session::get('today_follow_time')){
    Log::debug("today_follow_timeのセッションはありません。値を入れます");
    $nowtime = date("Y/m/d H:i:s");
    Session::put('today_follow_time', $nowtime);
  }

    return view('autofollow/addfollow');
}


  //ユーザーを1日に数人DB追加。cronで実施。ーーーーーーーーーーーーーーーー
  public static function addfollow(){

      $config = config('services');
      $consumerKey = $config['twitter']['client_id'];	// APIキー
      $consumerSecret = $config['twitter']['client_secret'];	// APIシークレット
      $accessToken = $config['twitter']['access_token'];	// アクセストークン
      $accessTokenSecret = $config['twitter']['access_token_secret'];	// アクセストークンシークレット

      $oAuth = new TwitterOAuth($consumerKey, $consumerSecret, $accessToken, $accessTokenSecret);
      //検索ワード
      $search_key = '仮想通貨';
      // 取得オプション
      $options = array('q'=>$search_key, 'count'=>100, 'lang' =>'ja','entities' => false, 'page' => 1,);
      $users_results = array();

      $options['page'] = 1;
      $results = $oAuth->get("users/search", $options);
      for($i=0; $i<20; $i++){
            $users_results[$i]['screen_name'] = $results[$i]->screen_name;
            $users_results[$i]['twitter_id'] = $results[$i]->id;
            $users_results[$i]['registtime'] = $results[$i]->created_at;
            $users_results[$i]['screen_name'] = $results[$i]->screen_name;
            $users_results[$i]['user_id'] = $results[$i]->id;
            $users_results[$i]['name'] = $results[$i]->name;
            $users_results[$i]['profile_image'] = $results[$i]->profile_image_url;
            $users_results[$i]['friends_count'] = $results[$i]->friends_count;
            $users_results[$i]['followers_count'] = $results[$i]->followers_count;
            $users_results[$i]['description'] = $results[$i]->description;
            $users_results[$i]['tweet'] = $results[$i]->status->text;
            $users_results[$i]['created_at'] = $results[$i]->created_at;
            $users_results[$i]['following'] = $results[$i]->following;

        }

        $options['page'] = 2;
        $results = $oAuth->get("users/search", $options);
        for($i=0; $i<20; $i++){
            $users_results[$i+20]['screen_name'] = $results[$i]->screen_name;
            $users_results[$i+20]['twitter_id'] = $results[$i]->id;
            $users_results[$i+20]['registtime'] = $results[$i]->created_at;
            $users_results[$i+20]['screen_name'] = $results[$i]->screen_name;
            $users_results[$i+20]['user_id'] = $results[$i]->id;
            $users_results[$i+20]['name'] = $results[$i]->name;
            $users_results[$i+20]['profile_image'] = $results[$i]->profile_image_url;
            $users_results[$i+20]['friends_count'] = $results[$i]->friends_count;
            $users_results[$i+20]['followers_count'] = $results[$i]->followers_count;
            $users_results[$i+20]['description'] = $results[$i]->description;
            $users_results[$i+20]['tweet'] = $results[$i]->status->text;
            $users_results[$i+20]['created_at'] = $results[$i]->created_at;
            $users_results[$i+20]['following'] = $results[$i]->following;

        }

        $options['page'] = 3;
        $results = $oAuth->get("users/search", $options);
        for($i=0; $i<20; $i++){
            $users_results[$i+40]['screen_name'] = $results[$i]->screen_name;
            $users_results[$i+40]['twitter_id'] = $results[$i]->id;
            $users_results[$i+40]['registtime'] = $results[$i]->created_at;
            $users_results[$i+40]['screen_name'] = $results[$i]->screen_name;
            $users_results[$i+40]['user_id'] = $results[$i]->id;
            $users_results[$i+40]['name'] = $results[$i]->name;
            $users_results[$i+40]['profile_image'] = $results[$i]->profile_image_url;
            $users_results[$i+40]['friends_count'] = $results[$i]->friends_count;
            $users_results[$i+40]['followers_count'] = $results[$i]->followers_count;
            $users_results[$i+40]['description'] = $results[$i]->description;
            $users_results[$i+40]['tweet'] = $results[$i]->status->text;
            $users_results[$i+40]['created_at'] = $results[$i]->created_at;
            $users_results[$i+40]['following'] = $results[$i]->following;
        }

        //同じscreen_nameがDB上autofollowsテーブルにあるかどうか確認し（第一引数）、
        //第二引数で情報を挿入または更新。
        for($i=0; $i<60; $i++){
          $autofollow = Autofollow::updateOrCreate(
          ['screen_name' => $users_results[$i]['screen_name']],//第一引数
          [//第二引数
          'screen_name' => $users_results[$i]['screen_name'],'twitter_id' => $users_results[$i]['twitter_id'],
          'name' => $users_results[$i]['name'],'text' => $users_results[$i]['tweet'],
          'registtime' => $users_results[$i]['registtime'],
          ]
        );
        }

      //print_r($users_results);
      header( "Content-Type: application/json; charset=utf-8" );//jsonデータに変換
      //jsonにする処理
      $results = json_encode($users_results,JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
      print_r($results);
      return view('autofollow/addfollow');
  }

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
      //header( "Content-Type: application/json; charset=utf-8" );//jsonデータに変換
      //jsonにする処理
     //$results = json_encode($users_results,JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
     //$results = json_decode($users_results,true);
    //print_r($results);

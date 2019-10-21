<?php

namespace App\Http\Controllers;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use App\Autofollow;
use App\User;
use App\Updatetime;
use Session;
use Abraham\TwitterOAuth\TwitterOAuth;

//まとめてフォロー関連のクラス。
//twitteroauth でサービスへのログインユーザーのツイッター情報を取得する。
//index:まとめてフォローのページ表示/followはフォローの実施アクション/allfollowはまとめてフォローの機能
//addfollowでdbに定期的（日に一度）ツイッターから情報を取得し、仮想通貨関連のアカウントを取り込む。
//（それを元にindex側でアカウントを表示します。）



class AutofollowController extends Controller
{

  //ログインユーザーのセッション情報を元に、ツイッター認証情報をまとめる関数。
  public function twitteroauth(){
    //Log::debug(Session('user_token'));
    //Log::debug(Session('user_tokensecret'));
    $config = config('services');
    $consumerKey = $config['twitter']['client_id'];	// APIキー
    $consumerSecret = $config['twitter']['client_secret'];	// APIシークレット
    $accessToken = (Session('user_token'));	// ログインユーザーのアクセストークン（twiitercontloreにて、セッション情報として保管）
    $accessTokenSecret = (Session('user_tokensecret'));	// ログインユーザーのアクセストークンシークレット
    $oAuth = new TwitterOAuth($consumerKey, $consumerSecret, $accessToken, $accessTokenSecret);
    return $oAuth;
  }


  //------------------------------------

  //オートフォロートップページ
  //Sessionに'today_follow_end';が入っていると本日の本サービスでのフォローはできないようにします。1日に385人以上を超えたら制限。
  public function index(){


    $autofollow_check = Auth::user()->autofollow;
    if($autofollow_check == 1){
      Session::put('autofollow', true);//セッションにも入れる。
    }
    Log::debug("ーーーーーーーーーーーーーーーーーーーーーーー");
    Log::debug("autofollow_checkの状態".$autofollow_check);
    Log::debug("0だとオートフォローは実施していません");
    Log::debug("ーーーーーーーーーーーーーーーーーーーーーーー");


//ここからは一旦消す
    //まずはまとめてフォローをできるかどうかの判定（15分ごとの判定）。■■■
    //$autofollow_readyが1ならフォローできない、0ならできる。
    //まずはセッションにtoday_follow_timeがあるかどうかを確認。ある場合はフラグを1にする。
    //

    if(Session::get('today_follow_time')){
      $autofollow_ready = 1;//1の場合はオートフォロー不可能。
      $nowtime = date("Y/m/d H:i:s");//現在時刻
      $last_followtime = Session::get('today_follow_time');
      //Log::debug('最後にオートフォローした時間です'.$last_followtime);

      //今の時間と、前回のフォロー時間をタイムスタンプに入れる。
      $timeStamp1 = strtotime($nowtime);
      $timeStamp2 = strtotime($last_followtime);

      //タイムスタンプの差を計算
      $difSeconds = $timeStamp1 - $timeStamp2;
      //Log::debug('$timeStamp1と2の差です。'.$difSeconds);

      $difMinutes = ($difSeconds - ($difSeconds % 60)) / 60;
      $diffTime = $difMinutes % 60;
      //Log::debug($diffTime."分経過しています。");

      if($diffTime > 14){//15分以上経過しているなら
      //  Log::debug("前回のまとめてフォローから15分経過しました！タイムをリセットします。");
        Session::forget('today_follow_time');
        $autofollow_ready = 0;//オートフォロー可能な状態
      }else{
      //  Log::debug("まだ15分経過していません");
      }

    }else{
      $autofollow_ready = 0;//オートフォロー可能な状態
    }

  //  Log::debug("まとめてフォローの状態です。".$autofollow_ready);
    Log::debug("ーーーーーーーーーーーーーーーーーーーーーーー");
  //  Log::debug("0だと実施可能です。１だと実施できません。");

//ここまでーーーーーーーーーーーーーーーーーー消す




    //■■■前回にフォローした日付（follow_day）をDBから確認し、違う日であればリセットする。（日本時間）■■■

    Log::debug("処理:DB上の前回のアクセス日と異なるかチェックします。");
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
      Auth::user()->update();
      Session::forget('today_follow_end');//フォロー自体できなくなる処理をリセット
      Session::forget('today_follow_time');//まとめてフォローをできなくなる処理もリセット
    }else{
      //db上のフォローをした日付と本日が同じ場合は特に何もしない
      Log::debug("以前の日付と同じです。フォロー数は維持されます。");
    }


    //次に、1日のフォロー数制限が385超えていたらフォローできないようにするフラグをonにする
    Log::debug("処理2:一日のフォロー数制限が385超えていたらフォローできないように制限します。");

    $follow_count = Auth::user()->follow_count;
    Log::debug("本日このサービスでフォローした数".$follow_count);
    if($follow_count > 385) //本来は385にする！
    {
      Log::debug("すでに385フォロー超えています。");
      Session::put('today_follow_end', true);

    }else{
      Log::debug("まだフォロー数は385を超えていません。");
      Session::put('today_follow_end', false);
    }




    //■■■アカウント一覧を表示させる処理■■■
    //もしセッション情報がない場合、Twitter認証をしていないということになるため、
    //ビュー側ではフォローできるアカウント情報は出さない。
    //代わりにajaxでdb上のユーザーデータを表示させる。値は$temp_userに入れます。

    $follow_users = array();
    for($i = 0; $i < 30; $i++){
      //DBからユーザーを30人、screen_nameのみランダムに取得し、$randomUserに詰め込む。
      $randomUser = Autofollow::inRandomOrder()->first();
      //それを$follow_usersに詰め込む
      array_push($follow_users,$randomUser->screen_name);
    }
    //$follow_usersはランダムにDBから取得したユーザー情報。
    //ツイッター認証していない場合はそのユーザーをそのまま表示させる。


    //ツイッター認証している場合は、$follow_usersから取得した中で、ログインユーザーが
    //「まだフォローしてないユーザーの情報のみ」を取得し$lookupuserに格納。
    //「まだフォローしてないユーザーの情報のみ」を画面に表示させます。
    $follow_users = implode(",", $follow_users);//クォーテーションをつける。
    $oAuth = $this->twitteroauth();//関数
    $lookupuser = $oAuth->get("users/lookup", ["screen_name" => "$follow_users"]);
    $temp_user = array();
    //取得データから「following」がfalse（フォローしてない）ユーザーであれば
    //$temp_userに格納（まとめてフォローは15分に1度実施可能にし、14人まで。）
    for($i=0; $i<15; $i++){
      if(!$lookupuser[$i]->following){
        $temp_user[] = ($lookupuser[$i]);
      }
    }
    //$temp_userをjsonエンコードし、$users_resultsユーザー情報を取得する。
    $users_results = json_encode($temp_user,JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);


    return view('autofollow/index',compact('users_results','follow_users','autofollow_ready','autofollow_check'));//$autofollow_readyを消すよ
    //viewに渡す変数の説明↓
    //$follow_usersはランダムにDBから取得したユーザー情報。
    //$users_resultsは、ログインユーザーがフォローしてないユーザー一覧のスクリーンネーム。
    //autofollow_readyは、まとめてフォローを実施できるかどうかの値。1ならできない、0ならできる。
    //autofollow_checkは、自動フォローの状態。0ならオートフォローは実施していない
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

    $now_follow_num = Auth::user()->follow_count;
    Log::debug("db上の数です。".$now_follow_num);
    $sum = $now_follow_num + 1;
    Log::debug("dbに1を足しました、saveします！db上の数は→".$sum);
    Auth::user()->follow_count = $sum;
    Auth::user()->update();

    return response()->json(['result' => true]);
  }





  //画面上の全ユーザーフォローアクションーーーーーーーーーーーーーーーー

  public function allfollow(Request $request){

    //Log::debug("リクエストの中身（フォロー対象のユーザー。）");  //フォローボタンを押した時に送られる中身
    Log::debug($request->allusers);
    $count = count($request->allusers);
    Log::debug("オールフォローを実施します。");
    Log::debug("フォローする人数です".$count);

    //$get_allfollow = explode(",", (array)$request->allusers->screen_name);//クォーテーションをつける。
    Log::debug("オールフォローを実施しました。");
    //Log::debug($get_allfollow);

    $now_follow_num = Auth::user()->follow_count;
    Log::debug("db上の数です。".$now_follow_num);
    $now_follow_num = $now_follow_num + $count;
    Log::debug("dbにオールフォロー数を足しました、saveします！db上の数は→".$now_follow_num);
    Auth::user()->follow_count = $now_follow_num;
    Auth::user()->update();

    $oAuth = $this->twitteroauth();
    foreach ($request->allusers as $value)
    {
      $target = $value['screen_name'];
      $oAuth->post("friendships/create", ["screen_name" => $target]);
      Log::debug($target."をフォローしました");
    }


    $now_follow_num = Auth::user()->follow_count;
    Log::debug("db上の数です。".$now_follow_num);
    $sum = $now_follow_num + 1;

    Log::debug("db上の本日のフォロー数はこちらに更新されます。→".$sum);
    Auth::user()->follow_count = $sum;
    Auth::user()->update();


    $now_follow_num = Auth::user()->follow_count;
    Log::debug("db上の数です。".$now_follow_num);
    $sum = $now_follow_num + 1;


    //まとめてフォローをしたらセッション[today_follow_time]に時間を入れる。
    //15分経過するまで次のまとめてフォローを実施できないようにする
    if(!Session::get('today_follow_time')){
      Log::debug("today_follow_timeのセッションはありません。値を入れます");
      $nowtime = date("Y/m/d H:i:s");
      Session::put('today_follow_time', $nowtime);
    }

    return;
  }




  //ーーーーーユーザーを1日に数人DB追加。cronで数回実施。依存ユーザーの情報がある場合はツイート更新。
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


    //ランダムにページ数を取得
    $num = mt_rand(1,10);
    $num2 = $num++;
    $num3 = $num2++;

    $options['page'] = $num;
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

    $options['page'] = $num2;
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

    $options['page'] = $num3;
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

    //updateOrCreateを使い同じscreen_nameがDB上autofollowsテーブルにあるかどうか確認し（第一引数）、
    //第二引数で情報を挿入、または既存のユーザー情報があるなら更新。
    for($i=0; $i<60; $i++){
      $autofollow = Autofollow::updateOrCreate(
        [//第一引数
          'screen_name' => $users_results[$i]['screen_name']
        ],
        [//第二引数
          'screen_name' => $users_results[$i]['screen_name'],'twitter_id' => $users_results[$i]['twitter_id'],
          'name' => $users_results[$i]['name'],'text' => $users_results[$i]['tweet'],
          'registtime' => $users_results[$i]['registtime'],
        ]
      );
    }
    //DB上の更新日時記録テーブルを更新
    date_default_timezone_set('Asia/Tokyo');
    $now_time = date("Y-m-d H:i:s");//今の時間
    Log::debug($now_time);
    $addusertime_update = Updatetime::where('id', 5)->first();//dbからデータ取得
    $data = ['updated_at' => $now_time];
    $addusertime_update->update($data);


    header( "Content-Type: application/json; charset=utf-8" );//jsonデータに変換
    //jsonにする処理
    $results = json_encode($users_results,JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    print_r($results);
    return;
  }






  //ーーーーーーオートフォロー。自動で行われる処理。15分に一度実施されます。
  //ーーーーーーDBからオートフォロー「1」にされていると実施される
  //----------自動処理、かつ複数ユーザーで行う可能性があるのでセッションは使わない
  public static function autofollow(){
    Log::debug("オートフォロー開始します");

    //DBからユーザーを20人、screen_nameのみランダムに取得し、$randomUserに詰め込む。
    //そのscreen_nameを$follow_targetsに詰め込む
    $follow_targets = array();
    for($i = 0; $i < 20; $i++){
      $randomUser = Autofollow::inRandomOrder()->first();
      array_push($follow_targets,$randomUser->screen_name);
    }
    //スクリーンネームにクォーテーションをつける
    $follow_targets = implode(",", $follow_targets);
    Log::debug("フォローターゲット");
    Log::debug($follow_targets);

    //フォロー元のユーザーアカウント（db上のautofollowが1のユーザー）を検索。
    //また、そのユーザー数をカウントする。
    $follow_acount = User::where('autofollow', 1)->get();
    Log::debug("autoフォロー1の人です。".$follow_acount);
    $num = count($follow_acount);
    Log::debug("numです".$num);
    if($num == 0){
      Log::debug("autoフォロー1の人がいません。終了します");
      return;
    }

//-------------
Log::debug("チェックdす");

    //カウント数までforで回し、フォロー元ユーザーのツイッター情報認証を取得。
    //フォローしていない ＝ followingがfalseのユーザーのみtempに詰め込む
    //1週目$iは0 numは2。
    for($i = 0; $i < $num; $i++){
      Log::debug("iの中身1です".$i);
      //1日のフォロー数制限が385超えていたらフォローできないようにするフラグをonにする
      //Log::debug("処理2:一日のフォロー数制限が385超えていたらフォローできないように制限します。");

            //一人の処理---------------■
            $follow_count = $follow_acount[$i]->follow_count;
            Log::debug("本日このサービスでフォローした数".$follow_count);

            if($follow_count > 385){
              Log::debug("すでに385フォロー超えています。処理を終了します。");

              }else{

                      Log::debug("まだフォロー数は385を超えていません。ここからフォロー処理を実施します。");
                      $config = config('services');
                      $consumerKey = $config['twitter']['client_id'];	// APIキー
                      $consumerSecret = $config['twitter']['client_secret'];	// APIシークレット
                      $accessToken = ($follow_acount[$i]->token);	// ログインユーザーのアクセストークン
                      Log::debug("アクセストークンです。".$accessToken);
                      $accessTokenSecret = ($follow_acount[$i]->tokensecret);	// ログインユーザーのアクセストークンシークレット
                      Log::debug("アクセストークンシークレットですです。".$accessTokenSecret);
                      $oAuth = new TwitterOAuth($consumerKey, $consumerSecret, $accessToken, $accessTokenSecret);
                      Log::debug($follow_acount[$i]->name."がフォローします");

                      //lookupで先ほど取得したscreen_nameを使い情報取得。そのままscreen_nameを$temp_userに詰め込む。
                      $lookupuser = $oAuth->get("users/lookup", ["screen_name" => "$follow_targets"]);
                      $temp_user = array();

                    //フォロー対象のまとめ。f
                    for($f=0; $f<2; $f++){
                        if(!$lookupuser[$f]->following){
                          array_push($temp_user,$lookupuser[$f]->screen_name);
                          Log::debug(count($temp_user));
                          Log::debug($temp_user);
                          }

                        if(count($temp_user) == 2){
                          Log::debug("2人に達しました。フォロー対象の検索はここまでです。forを抜けます。");
                          break;
                          }

                    }


                    //実際のフォロー
                    Log::debug("tempユーザー一覧です。このユーザーたちをフォローします。");
                    Log::debug($temp_user);

                    foreach ($temp_user as $value)
                    {
                      //Log::debug($value);
                      $target = $value;
                      Log::debug("ターゲットの中身".$target);
                      $oAuth->post("friendships/create", ["screen_name" => $target]);
                      Log::debug($target."をフォローしました");
                    }

                    //フォローした数をカウントとしてdbに追加。
                    $count = count($temp_user);
                    Log::debug("フォローした人数です".$count);
                    $now_follow_num = $follow_acount[$i]->follow_count;//処理中のユーザーのカウント数
                    Log::debug("db上の数です。".$now_follow_num);
                    $now_follow_num = $now_follow_num + $count;
                    Log::debug("dbにオールフォロー数を足しました、saveします！db上の数はこちらに更新されます→".$now_follow_num);

                    $now_usertwiiter_id = $follow_acount[$i]->twitter_id;//処理中のユーザーのツイッターid
                    Log::debug("処理中のユーザーのツイッターidです".$now_usertwiiter_id);

                    $user = User::where('twitter_id', $now_usertwiiter_id)->first();
                    $user->follow_count = $now_follow_num;
                    $user->update();
                    //Log::debug("DBを更新しました。多分");


              Log::debug("フォロー処理を終了します。");

            }
  }
}



}//classの閉じタグ

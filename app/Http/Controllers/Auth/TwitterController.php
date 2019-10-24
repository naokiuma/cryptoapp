<?php

namespace App\Http\Controllers\Auth;
use App\User;
use Auth;
use Socialite;
use Session;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Log;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

//Socialite::driver('twitter')->user();で
//返ってくるオブジェクトはTwitterのアカウント名や、
//画像URLなどなどかなり多くの情報を持っています。

class TwitterController extends Controller
{
  use AuthenticatesUsers;

  //ツイッターの認証ページへリダイレクト。
  public function redirectToProvider(){
    //コールバック関数により本ファイル28行目handleProviderCallbackアクションへ。
    return Socialite::driver('twitter')->redirect();
  }



  //ツイッターからユーザー情報を取得する関数。
  public function handleProviderCallback(){
    try{
      $twitterUser = Socialite::driver('twitter')->user();

      $user_token = $twitterUser->token;
      $user_tokensecret = $twitterUser->tokenSecret;
      //セッション情報としてツイッターユーザーの情報を保持。
      Session::put('user_token', $user_token);
      Session::put('user_tokensecret', $user_tokensecret);
      //Log::debug("ログインユーザーのトークンとシークレットトークンです。");
      //Log::debug(Session('user_token'));
      //Log::debug(Session('user_tokensecret'));

    }catch (Exception $e){//ログインユーザーを取得できない場合は再度認証ページへ
      return redirect('auth/twitter');
    }

    //ログインしているか確認
    if(Auth::check()){
      //ログインしている＝すでにユーザー登録済みなので、ユーザーIDを取得し
      //そのカラムにツイッター情報を追加する

      $user_id = Auth::user()->id;
      $user_date = User::where('id',$user_id)->first();


      //Log::debug('最新のTwitter情報をdbに登録します。');
      //userカラムのtwiiter関連データにツイッター情報を挿入
      $user_date->fill([
        'twitter_id' => $twitterUser->id,
        'handle' => $twitterUser->nickname,
        'avatar' => $twitterUser->avatar_original,
        'token' => $user_token,
        'tokensecret' => $user_tokensecret
        ])->save();
        Auth::login($user_date, true);
        return redirect()->route('autofollow');

        //ログインしてないなら、ツイッターアカウントののあるユーザーに登録しログインする
      }else{
        $user_date = $this->findOrCreateUser($twitterUser);
        Auth::login($user_date, true);
        return redirect()->route('top');
      }
    }

    //ログインしていない状態でツイッターデータのあるカラムを探し、なければ作る。
    private function findOrCreateUser($twitterUser){
      //Log::debug(print_r("findOrCreateUser実施", true));
      $user_date = User::where('twitter_id',$twitterUser->id)->first();
      //ツイッターのidがすでにテーブルにあれば同じツイッターidのユーザー情報を返す
      if($user_date){
        Log::debug(print_r("twiiteridがDBにあり", true));
        return $user_date;
      }else{
        //なければそのまま作成。
        Log::debug(print_r("twiiteridがDBになし", true));
        return User::create([
          'twitter_id' => $twitterUser->id,
          'handle' => $twitterUser->nickname,
          'avatar' => $twitterUser->avatar_original
        ]);
      }
    }


    public function logout(){
      Auth::logout();
      Session::flush();
      return redirect()->route('top');
    }

    public function __construct(){
      $this->middleware('guest')->except('logout');
    }

  }

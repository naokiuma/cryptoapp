<?php

namespace App\Http\ViewComposers;

use Illuminate\Contracts\View\View;
use Illuminate\Contracts\Auth\Guard;

class UserComposer{
  //このクラス中でだけ使うauthという変数を作る
  protected $auth;
  //ここでguardをすることで、この引数の中に、いろんな認証系の情報が入ってくる。
  public function __construct(Guard $auth)
  {//初期化の際にauthに詰め込む。
    $this->auth = $auth;
  }
  //メソッド名composeは決まっている。またその引数にはViewをとる。
  public function compose(View $view)
  {
    //viewに渡したい変数をuserという変数を使えるようにし、
    //その中に$this->auth->user()という値を詰めてビューに渡す、という定義の仕方。
    //これはauthファサードと同じように、ユーザー情報を取得できる。
    $view->with('user', $this->auth->user());

  }
}

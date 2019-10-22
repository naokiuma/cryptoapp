<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Auth::routes();
Route::get('/', function () {
    return view('top');
})->name('top');

Route::get('/about', function () {
    return view('about');
})->name('about');


//コインコントローラー
Route::get('coin','CoinController@index')->name('coin');//コイン情報まとめページへのリンク
Route::get('coin/hour','CoinController@hour')->name('coin.hour');//1日のツイート数を検索。cronで実施。
Route::get('coin/day','CoinController@day')->name('coin.day');//1日のツイート数を検索。cronで実施。
Route::get('coin/week','CoinController@week')->name('coin.week');//1日のツイート数を検索。cronで実施。
Route::get('coin/highandlow','CoinController@highandlow')->name('coin.highandlow');//1日のツイート数を検索。cronで実施。


//ニュースコントローラー
Route::get('news','NewsController@index')->name('news');


//オートフォローコントローラー（フォローを実施できるページ）
Route::get('autofollow','AutofollowController@index')->name('autofollow');//表示
Route::post('autofollow','AutofollowController@follow')->name('autofollow.follow');//フォロー
Route::post('autofollow/all','AutofollowController@allfollow')->name('autofollow.all');//自動フォローをonにする処理
Route::get('autofollow/addfollow','AutofollowController@addfollow')->name('autofollow.addfollow');//DBにツイッターアカウントを追加。cronに追加する処理
Route::get('autofollow/autofollow','AutofollowController@addfollow')->name('autofollow.autofollow');//自動フォロー。15分に一度行う。




// 認証系。ツイッターログインURL
Route::get('auth/twitter', 'Auth\TwitterController@redirectToProvider')->name('auth.twitter');
// コールバックURL
Route::get('auth/twitter/callback', 'Auth\TwitterController@handleProviderCallback')->name('auth.twitter.callback');
// ログアウトURL
Route::get('auth/twitter/logout', 'Auth\TwitterController@logout')->name('auth.twitter.logout');

//ajaxのデータを表示させるvuew
Route::get('ajax/coin', 'AjaxController@coin')->name('ajax.coin');;
Route::get('ajax/users', 'AjaxController@users')->name('ajax.users');;



//ミドルウェア--------------------------------------------------
//ログインしていない場合はリダイレクトさせるページ
Route::group(['middleware' => 'check'],function(){
  Route::get('auth/twitter', 'Auth\TwitterController@redirectToProvider');
  Route::get('autofollow','AutofollowController@index')->name('autofollow');
  Route::get('coin','CoinController@index')->name('coin');
});

//逆にログインしている場合はトップにリダイレクトさせるページ。（パスワードリマインダーなど）
Route::group(['middleware' => 'logout'],function(){
  Route::get('register', 'Auth\RegisterController@showRegistrationForm')->name('register');
  Route::get('password/reset','Auth\ForgotPasswordController@showLinkRequestForm')->name('password.request');
  Route::get('login','Auth\LoginController@showLoginForm')->name('login');
});

//自動フォローのonoffチェック処理を下記、各ページにアクセスするたびに行う
Route::group(['middleware' => 'allfollow'],function(){
  Route::get('/', function () { return view('top'); })->name('top');
  Route::get('/about', function () {return view('about');})->name('about');
  Route::get('news','NewsController@index')->name('news');
  Route::get('coin','CoinController@index')->name('coin');
});

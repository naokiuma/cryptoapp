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

//コインコントローラー
Route::get('coin','CoinController@index')->name('coin');
Route::get('coin/hour','CoinController@hour')->name('coin/hour');//1日のツイート数を検索。cronで実施。
Route::get('coin/day','CoinController@day')->name('coin/day');//1日のツイート数を検索。cronで実施。
Route::get('coin/week','CoinController@week')->name('coin/week');//1日のツイート数を検索。cronで実施。


//ニュースコントローラー
Route::get('news','NewsController@index')->name('news');
//ニュースの中の更新コントローラー（テスト）
//Route::get('news/test','NewsController@test')->name('test');
//zaifのデータを取得テスト
Route::get('news/test2','NewsController@test2')->name('test2');

//オートフォローコントローラー
Route::get('autofollow','AutofollowController@index')->name('autofollow');//表示
Route::post('autofollow','AutofollowController@follow');//フォロー
Route::post('autofollow/all','AutofollowController@allfollow');//フォロー
Route::get('autofollow/addfollow','AutofollowController@addfollow');//DBにツイッターアカウントを追加。cronに追加する処理





// 認証系。ツイッターログインURL
Route::get('auth/twitter', 'Auth\TwitterController@redirectToProvider');
// コールバックURL
Route::get('auth/twitter/callback', 'Auth\TwitterController@handleProviderCallback');
// ログアウトURL
Route::get('auth/twitter/logout', 'Auth\TwitterController@logout');

//ajaxのデータを表示させるvuew
Route::get('ajax/coin', 'AjaxController@coin');
Route::get('ajax/users', 'AjaxController@users');



//ミドルウェア--------------------------------------------------
//ログインしていない場合はリダイレクトさせるページ
Route::group(['middleware' => 'check'],function(){
  Route::get('auth/twitter', 'Auth\TwitterController@redirectToProvider');
  Route::get('autofollow','AutofollowController@index')->name('news');
  Route::get('coin','CoinController@index')->name('coin');
});

//逆にログインしている場合はトップにリダイレクトさせるページ。（パスワードリマインダーなど）
//何かおかしいので、これらのページ側でログインしてたらリダイレクトさせる。
//        return redirect('/')->with('flash_message',__('すでにログインしています。'));

//Route::group(['middleware' => 'logout'],function(){
//  Route::get('register', 'Auth\RegisterController@showRegistrationForm');//何かおかしいログウト状態で行ったらエラー
//  Route::get('password/reset','Auth\ForgotPasswordController@showLinkRequestForm');
//  Route::get('login','Auth\LoginController@showLoginForm');//何かおかしい
//});

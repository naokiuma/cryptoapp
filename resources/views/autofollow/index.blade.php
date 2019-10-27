@extends('layouts.app')
@section('title', 'まとめてフォロー')
@section('description', 'まとめてフォロー機能は、「仮想通貨」に関するツイッターのアカウントを効率よくフォローする機能です。
自動フォロー：ONで定期的にフォローを実行します。')
@section('keywords', 'CryptoTrend,Twitter,ツイッター,仮想通貨,まとめてフォロー,自動フォロー,暗号通貨,Cryptocoin')

@section('content')

<div class="p-desc__container">
  <h2 class="p-desc__title c-text">
    <i class="fab fa-twitter"></i>まとめてフォロー
  </h2>
  <p class="p-desc__text c-text">
    Twitter上の『仮想通貨』関連ユーザーをフォローし、情報のキャッチアップを効率的に行いましょう。
  </p>
</div>
<div class="u-mark__small">本機能の仕組みについては<a href="{{ url('about') }}/#about_twitter" target="_blank">[こちら]</a>を参照してください。</div>



@if (session('today_follow_end'))
<!--セッション情報にtoday_follow_endが入っている場合、本日のフォローができない。-->>

<div class="p-desc__container">
  <p class="p-desc__text c-text">
    本日はすでに多くのフォローを実施しているため、フォローは実施できません。<br>
    明日以降アクセスしてください。<br>
    <a href="{{ url('about') }}/#about_limit">※フォロー制限について</a>
  </p>
  <div class="u-short"></div>

  <!--ユーザーのツイッター情報がないので、管理者の引っ張ってきた情報を見本として表示-->
  <div id="nologinapp">
   <Nologin-component
    autofollowsample_ajax = "{{ url('autofollow/addfollow') }}">
   </Nologin-component>
  </div>
</div>

@else

<section class="l-main__twitter">
  @if (session('user_token'))
  <!--ツイッター認証をしている場合は下記コンポーネントを表示。受け渡す変数の内容は以下の通りです。-->
  <!--autofollow_checkはセッションの状態。1ならば自動フォロー実施中。-->
  <!--users_resultsはログインユーザーがフォローしてないユーザー一覧のスクリーンネーム。-->
  <!--autofollow_ajaxは個別フォローするurlへのポストの時のurl-->
  <!--autofollowall_ajaxは自動フォローをonにするポストのurl-->

  <!--$autofollow_checkこの値で現在オートフォロー中か判断-->
  <div id="twitterapp">
    <Twitter-component
    :users_results="{{ $users_results }}"
    follow_users="{{$follow_users}}"
    autofollow_check = "{{ $autofollow_check }}"
    autofollow_ajax = "{{ url('autofollow') }}"
    autofollowall_ajax = "{{ url('autofollow/all') }}"
    >
  </Twitter-component>
</div>

@else


<!--ツイッター認証をしていない場合は下記を表示-->
<div class="c-text p-twiiter__top">
  <p>各アカウントのフォローをするには<br>「Twitter認証」をしてください。</p>
  <a href="auth/twitter" class=""><i class="fab fa-twitter"></i>Twitter認証を行う。</a>
</div>

<!--ユーザーのツイッター情報がないので、情報を見本としてコンポーネントで表示-->
<!--autofollowsample_ajaxは、DBから取得するためのajax用の変数-->
<div id="nologinapp">
  <Nologin-component
  autofollowsample_ajax = "{{ url('autofollow/addfollow') }}"
  ></Nologin-component>
</div>
@endif
</section>
@endif



@endsection

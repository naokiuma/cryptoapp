@extends('layouts.app')

@section('content')


<div class="p-desc__container">

  <h2 class="p-desc__title c-text">
    <i class="fab fa-twitter"></i>まとめてフォロー機能
  </h2>
  <p class="p-desc__text c-text">
    Twitter上で『仮想通貨』という名称を含むプロフィールやアカウント一覧を表示します。<br>
    ※ログインアカウントの未フォローユーザーが表示されます。<br>
    本機能の仕組みについては<a href="/about/#about_twitter" target="_blank">[こちら]</a>を参照してください。
  </p>
</div>


<div class="l-billboard__mini">
  <img alt="ツイッター" src="{{ asset('/img/tw_sp.png') }}">
</div>

@if (session('today_follow_end'))

<div class="p-desc__container">
  <p class="p-desc__text c-text">
    本日はすでに多くのフォローを実施しているため、まとめてフォロー、個別フォローもできません。<br>
    明日以降アクセスしてください。<br>
    <a href="#">※フォロー制限について</a>
  </p>
  <div class="u-short"></div>

  <!--ユーザーのツイッター情報がないので、管理者の引っ張ってきた情報を見本として表示-->
  <div id="nologinapp">
    <Nologin-component
    autofollowsample_ajax = "{{ url('autofollow/addfollow') }}"
    ></Nologin-component>
  </div>


</div>

@else

<section class="l-main__twitter">

@if (session('user_token'))

<!--ツイッター認証をしている場合は下記を表示-->
<!--autofollow_readyが1の場合、前にオールフォローしてから15分経過してないので、フォローできない。0の場合、フォローして良い。-->

<div id="twitterapp">
  <Twitter-component
  :users_results="{{ $users_results }}"
  follow_users="{{$follow_users}}"
  autofollow_ready = "{{ $autofollow_ready }}"
  autofollow_ajax = "{{ url('autofollow') }}"
  autofollowall_ajax = "{{ url('autofollow/all') }}"
  >
  </Twitter-component>
</div>

@else


<!--ツイッター認証をしていない場合は下記を表示-->
<div class="c-text p-twiiter__top">
  <p>各アカウントのフォローをするには<br>「Twitter」認証をしてください。</p>
  <a href="auth/twitter" class=""><i class="fab fa-twitter"></i>Twitter認証を行う。</a>
</div>

<!--ユーザーのツイッター情報がないので、情報を見本として表示-->
<div id="nologinapp">
  <Nologin-component
  autofollowsample_ajax = "{{ url('autofollow/addfollow') }}"
  ></Nologin-component>
</div>


@endif

</section>

@endif

@endsection

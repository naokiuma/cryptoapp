@extends('layouts.app')

@section('content')
<div class="p-desc__container">

  <h2 class="p-desc__title c-text">
    <i class="fas fa-coins"></i>コインTweetランキング
  </h2>
  <p class="p-desc__text c-text">
    Twitter上で各コインについて、つぶやかれた数でカウントをとりました。<br>
    今、SNSでどのコインが話題になっているか確認しましょう。<br>
    ※1時間/1日/1週間単位で集計しています。<br>
    本機能の仕組みについては<a href="{{ url('about') }}/#about_coin" target="_blank">[こちら]</a>を参照してください。

  </p>
</div>
<div class="l-billboard__mini">
  <img alt="コイン" src="{{ asset('/img/check-hard.png') }}">
</div>

<!--コントローラーから持ってきたデータ諸々。-->
<div id="coinapp">
    <coin-component
    coin_ajax="{{ url('ajax/coin') }}"
    hour = "{{$hour}}"
    day = "{{$day}}"
    week = "{{$week}}"
    >
  </coin-component>
</div>

@endsection

@extends('layouts.app')
@section('title', '通貨トレンド')
@section('description', '通貨トレンドは、各種仮想通貨のツイート数を集計し、今トレンドのコイン情報をランキング形式で確認することができます。')
@section('keywords', 'CryptoTrend,Twitter,ツイッター,仮想通貨,通貨トレンド,暗号通貨,Cryptocoin,取引価格')

@section('content')
<div class="p-desc__container">
  <h2 class="p-desc__title c-text">
    <i class="fas fa-coins"></i>通貨トレンド
  </h2>
  <p class="p-desc__text c-text">
    Twitter上の各コインのツイート数をカウントしました。<br>
    いつ、どのコインが話題になっているか？コインのトレンドを確認しましょう。<br>
  </p>
</div>
<div class="u-mark__small">※過去1時間/1日/1週間単位で集計しています。</div>
<div class="u-mark__small">本機能の仕組みについては<a href="{{ url('about') }}/#about_coin" target="_blank">[こちら]</a>を参照してください。
</div>

<!--コントローラーから持ってきたデータ。-->
<!--coin_ajaxはcoinのデータを取得するためのajaxに使うURL。-->
<!--hour,day,weekはそれぞれ期間分のツイート数を取得したもの。-->

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

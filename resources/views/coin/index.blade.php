@extends('layouts.app')

@section('content')
<div class="p-desc__container">

  <h2 class="p-desc__title c-text">
    <i class="fas fa-coins"></i>コインTweetランキング
  </h2>
  <p class="p-desc__text c-text">
    Twitter上で各コインについて、つぶやかれた数でカウントをとりました。<br>
    今、SNSでどのコインが話題になっているか確認しましょう。<br>
    ※1時間/1日/1週間単位で集計しています。
  </p>
</div>

<div class="l-billboard__mini" style="background-image: url('../img/check-hard.png')" >
</div>

<div id="coinapp">
    <coin-component></coin-component>
    <!--全てajaxでajax/coinsから取得-->
</div>

@endsection

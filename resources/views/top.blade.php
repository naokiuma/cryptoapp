@extends('layouts.app')
@section('title', 'TOP')
@section('description', 'CryptoTrendは、SNSサービスツイッターを利用し、仮想通貨に関する情報を集約し、情報のキャッチアップの手助けをするサービスです。')
@section('keywords', 'CryptoTrend,Twitter,ツイッター,仮想通貨,TOP,自動フォロー,通貨トレンド,通貨ニュース,暗号通貨')

@section('content')
<!--ビルボード-->
<section class="l-billboard" id="top">
  <div class="p-hero__title">
    <h2>CryptoTrend</h2>
    <p>SNSを使い仮想通貨の<br>
      情報を集めるお手伝いをするサービスです。
    </p>
  </div>
</section>


<!--サービスの説明-->
<section class="p-decs__main">
  <div class="p-desc__container">

    <h2 class="p-desc__title c-text">
      CryptoTrendとは？
    </h2>
    <p class="p-desc__text c-text">
      仮想通貨取引が始まり数年。<br>
      取引可能な通貨の種類は多く、ユーザーの各通貨への興味の推移や関連ニュースは非常に多種多様です。<br>
      通貨の取引にあたり、こういった情報を抑える作業は欠かせません。<br>
      『CryptoTrend』は、仮想通貨をこれから始める方、初めて間もない方に向け、情報収集のお手伝いをするサービスです。<br>
      <br>
    </p>
  </div>
 <div class="">
   <img style="height:200px" class="" src="{{ asset('/img/tw_sp.png') }}" alt="スマートフォン用の画像" >
   <img style="height:200px" class="" src="{{ asset('/img/tw_sp.png') }}" alt="スマートフォン用の画像" >
   <img style="height:200px" class="" src="{{ asset('/img/tw_sp.png') }}" alt="スマートフォン用の画像" >

 </div>

</section>


<!--サービスの説明続き-->
<h2 class="c-text__attention u-short">
  <span>こんなことに困っていませんか？</span>
</h2>

<section class="p-plobrem__container">
  <div class="p-plobrem">

    <h4>情報をどう探せばいいのかわからない。</h4>
    <img alt="サーチ" src="{{ asset('/img/search.jpg') }}">

  </div>
  <div class="p-plobrem">

    <h4>情報をまとめるのに時間がかかる。</h4>
    <img alt="サーチ" src="{{ asset('/img/search2.jpg') }}">
  </div>
</section>


<div class="u-short">
  <i class="fas fa-arrow-down"></i>
</div>


<!--機能紹介-->

<div class="arrow_box">
  <p class="c-arrow_box_logo">CryptoTorendなら</p>
</div>

<h2 class="c-text u-short">
  <span>3つの機能で情報収集をお手伝いします！</span>
</h2>

<div class="u-short">
  まとめてフォロー
  通貨トレンド
  仮想通貨ニュース
</div>


<section class="l-main__sub__container">

  <section class="l-main__sub">

    <div class="c-box u-width__wide">
      <h2>まとめてフォロー</h2>
      <p>SNSサービス『Twitter』からプロフィールや名前に<br>『仮想通貨』と入っているアカウントのみを表示させました。<br>
        <br>
        一人一人選んでフォローはもちろん、<br>画面一覧のアカウントを「まとめてフォロー」することも可能です。<br>
        ※「まとめてフォロー」は<a href="https://help.twitter.com/ja/using-twitter/twitter-follow-limit">Twitter社の仕様</a>に準じたフォローの制限数を設けています。

      </p>
      <div class="c-box__sp"><!--スマホのみ表示-->
        <i class="fab fa-twitter c-box__icon"></i>
      </div>

    </div>

    <div class="c-box u-width__narrow"><!--スマホでは非表示-->
      <i class="fab fa-twitter fontIcon"></i>
    </div>
  </section>



  <section class="l-main__sub">

    <div class="c-box u-width__narrow"><!--スマホでは非表示-->
      <i class="fas fa-coins fontIcon"></i>
    </div>

    <div class="c-box u-width__wide">
      <h2>通貨トレンド</h2>
      <p>今どのコインがツイッターで話題なのか？<br>
        各通貨のツイート数に応じて、ランキング形式でお伝えします。<br>
        ※過去[1時間/1日/一週間]ベースで集計。
      </p>

      <div class="c-box__sp"><!--スマホのみ表示-->
        <i class="fas fa-coins c-box__icon"></i>
      </div>
    </div>

  </section>



  <section class="l-main__sub">

    <div class="c-box u-width__wide">
      <h2>仮想通貨ニュース</h2>
      <p class="c-text">界隈のニュースも見逃さないようにしましょう。<br>
        仮想通貨関連のキーワードを持つGoogleニュースのみまとめました。
      </p>
      <div class="c-box__sp"><!--スマホのみ表示-->
        <i class="far fa-newspaper c-box__icon"></i>
      </div>
    </div>

    <div class="c-box u-width__narrow"><!--スマホでは非表示-->
      <i class="far fa-newspaper fontIcon"></i>
    </div>

  </section>
</section>



<div class="p-desc__container">

  <h2 class="p-desc__title c-text">
    さっそく始めよう。
  </h2>
</div>
<!--各種機能へのリンクとイメージ-->

<section class="p-functions__container u-mouseover__resurt">
  <div class="p-functions__twiiter u-mouseover__twitter">
    <div class="c-text">
      <a href="{{ url('autofollow') }}"><h3>まとめてフォロー</h3></a>
    </div>
    <a href="{{ url('autofollow') }}">
      <img class="p-functions__sp" src="{{ asset('/img/tw_sp.png') }}" alt="スマートフォン用の画像" >
    </a>
  </div>

  <div class="p-functions__coins u-mouseover__coins">
    <div class="c-text">
      <a href="{{ url('coin') }}"><h3>通貨トレンド情報</h3></a>
    </div>
    <a href="{{ url('coin') }}">
      <img class="p-functions__sp" src="{{ asset('/img/check-hard.png') }}" alt="スマートフォン用の画像" >
    </a>
  </div>

  <div class="p-functions__news u-mouseover__news">
    <div class="c-text">
      <a href="{{ url('news') }}"><h3>仮想通貨ニュース</h3></a>
    </div>
    <a href="{{ url('news') }}">
      <img class="p-functions__sp" src="{{ asset('/img/swiper_news.jpg') }}" alt="スマートフォン用の画像" >
    </a>
  </div>

</section>



@guest
<!--ログインしていない場合はsignupボタン-->

<h2 class="c-text u-short">
  <span>未登録の方はこちら</span>
</h2>

<section class="l-main__mini">

  <div class="p-start">
    <button type="button" name="button"><a href="{{ url('register') }}">SiGN UP</a></button>
    <div class="p-start__under">
      <p>※メールアドレス、Twitterアカウントが必要です。</p>
    </div>
  </div>
</section>

@endguest

<div class="u-short">
  <h2><a href="#top">topへ戻る</a></h2>

</div>

@endsection

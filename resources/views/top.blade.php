@extends('layouts.app')

@section('content')
<!--ビルボード-->
<section class="l-billboard">
    <div class="p-hero__title">
      <h2>CryptoTrend</h2>
      <p>SNSを使い仮想通貨の<br>
      情報を集めるお手伝いをするサービスです。</p>
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
      『CryptoTrend』は、そんな情報収集のお手伝いをするサービスです。<br>
      <br>
    </p>
  </div>
</section>

<h2 class="c-text__attention u-short">
  <span>こんなことに困っていませんか？</span>
</h2>

<section class="p-plobrem__container">
  <div class="p-plobrem">

    <h4>情報をどう探せばいいのかわからない。</h4>
    <img alt="サーチ" src="{{ asset('/img/search.jpg') }}">

  </div>
  <div class="p-plobrem">

    <h4>関連情報を探してまとめるのに時間がかかる。</h4>
    <img alt="サーチ" src="{{ asset('/img/search2.jpg') }}">

  </div>

</section>

<div class="u-short">
  <i class="fas fa-arrow-down"></i>
</div>




<h2 class="c-text u-short">
  <span>3つの機能でお手伝いします！</span>
</h2>


<section class="l-main__sub__container">


  <section class="l-main__sub">

      <div class="c-box u-width__wide">
        <h2>まとめてフォロー</h2>
        <p>SNSサービス『Twitter』からプロフィールや名前に『仮想通貨』と入っているアカウントのみを表示させました。<br>
        <br>
        一人一人選んでフォローはもちろん、画面一覧のアカウントを「まとめてフォロー」することも可能です。<br>
        ※「まとめてフォロー」は<a href="https://help.twitter.com/ja/using-twitter/twitter-follow-limit">Twitter社の仕様</a>に準じたフォローの制限数を設けています。

        </p>
        <div class="c-box__sp"><!--スマホのみ表示-->
          <i class="fab fa-twitter c-box__icon"></i>
        </div>

      </div>

      <div class="c-box u-width__narrow"><!--スマホでは非表示-->
        <i class="fab fa-twitter c-fonticon"></i>
      </div>
  </section>



  <section class="l-main__sub">

      <div class="c-box u-width__narrow"><!--スマホでは非表示-->
        <i class="fas fa-coins c-fonticon"></i>
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
        仮想通貨関連のキーワードを持つGoogleニュースのみまとめました。</p>
        <div class="c-box__sp"><!--スマホのみ表示-->
          <i class="far fa-newspaper c-box__icon"></i>
        </div>
      </div>

      <div class="c-box u-width__narrow"><!--スマホでは非表示-->
        <i class="far fa-newspaper c-fonticon"></i>
      </div>

  </section>
</section>


<div class="p-desc__container">

<h2 class="p-desc__title c-text">
  さっそく始めよう。
</h2>

</div>
<!--
<h2 class="c-text u-short">
  <span>さっそく始めよう。</span>
</h2>
-->

  <section class="p-functions__container u-mouseover__resurt">
      <div class="p-functions__twiiter u-mouseover__twitter">
        <div class="c-text">
          <a href="{{ url('autofollow') }}"><h3>まとめてフォロー</h3></a>
        </div>
        <img class="p-functions__sp" src="{{ asset('/img/tw_sp.png') }}" alt="スマートフォン用の画像" >
      </div>

      <div class="p-functions__coins u-mouseover__coins">
        <div class="c-text">
          <a href="{{ url('coin') }}"><h3>通貨トレンド情報</h3></a>
        </div>
        <img class="p-functions__sp" src="{{ asset('/img/check-hard.png') }}" alt="スマートフォン用の画像" >

      </div>
      <div class="p-functions__news u-mouseover__news">
        <div class="c-text">
          <a href="{{ url('news') }}"><h3>仮想通貨ニュース</h3></a>
        </div>
        <img class="p-functions__sp" src="{{ asset('/img/swiper_news.jpg') }}" alt="スマートフォン用の画像" >

      </div>
</section>

<h2 class="c-text u-short">
  <span>未登録の方はこちら</span>
</h2>


<!--早速始める情報-->
<section class="l-main__mini">
  <div class="p-start__container">

  <div class="p-start">
    <div class="p-start__text">
      <p>SiGN UP</p>
    </div>
    <div class="p-start__under">
      <p>※メールアドレス、Twitterアカウントが必要です。</p>
    </div>
  </div>
  </div>
</section>

<footer>
  <div class="l-footernavi__container">

    <ul>
      <li><a href="{{ url('register') }}"><i class="fas fa-play"></i>新規登録</a></li>
      <li><a href="{{ url('login') }}"><i class="fas fa-sign-in-alt"></i>ログイン</a></li>
      <li><a href="{{ url('password/reset') }}"><i class="fas fa-key"></i>パスワードを忘れた方</a></li>
    </ul>

    <ul>
      <li><a href="{{ url('autofollow') }}"><i class="fab fa-twitter"></i>まとめてフォロー</a></li>
      <li><a href="{{ url('coin') }}"><i class="fas fa-coins"></i>通貨トレンド</a></li>
      <li><a href="{{ url('news') }}"><i class="far fa-newspaper"></i>仮想通貨ニュース</a></li>
    </ul>

    <ul>
      <li>閲覧環境</li>
      <li>Twitterフォロー制限について</li>
    </ul>

  </div>
</footer>


@endsection

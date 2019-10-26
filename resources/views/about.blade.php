@extends('layouts.app')
@section('title', 'よくある質問')
@section('description', '本ページではサービスの機能概要、ご質問などについてご案内いたします')
@section('keywords', 'CryptoTrend,Twitter,ツイッター,仮想通貨,TOP,自動フォロー,通貨トレンド,通貨ニュース,暗号通貨,サービス説明')
@section('content')

<section class="p-decs__main">
  <div class="p-desc__container">
    <h2 class="p-desc__title c-text">
      <div class="p-about__title">
        About/よくある質問
      </div>
    </h2>
  </div>
</section>

<section class="p-about__container"  id="about_twitter">
  <div class="p-about__title">
    <h2>Q.本サービスの機能について教えてください。</h2>
  </div>
  <div class="p-about__contents">
    <h3>【１】まとめてフォロー</h3>
    <p>仮想通貨関連のアカウントを抽出し、一覧表示しました。<br>
      ユーザーリストは毎日深夜に更新されます。<br>
      Twitterアカウントを認証することで、<span>登録したアカウントがまだフォローしていないアカウント一覧</span>を画面に表示させ、
      効率的に一人一人フォローすることができます。</p>
      <br>

    <h4>■自動フォローと自動フォロー中マークについて</h4>
    <p>まとめてフォローボタンをクリックすると、
    <span>サービスにアクセスしていない間も、自動フォロー</span>する事も可能です。
    自動フォローをONにすると、ボタンカラーが変わり、またメニュー部分に自動フォロー中のマークが表示されます。</p>
    <img src="{{ asset('/img/auto_on.png') }}" alt="まとめてフォローON">
    <img src="{{ asset('/img/badge.png') }}" alt="まとめてフォローバッジ">

    <h4 id="about_limit">■1日のフォロー数について</h4>
    <p>Twitterは、1日のフォロー数を400人までにするよう制限をしています。<br>
    詳しくは<a href="https://help.twitter.com/ja/using-twitter/twitter-follow-limit" target="_blank">[こちら]</a>を参照してください。<br>
    本サービスでは、1日に395人のフォローカウントを超えると、<br>
    個別フォローが利用できないよう制限をかけています。<br>
    なお、本サービスで1日にフォローした人数はメニューの「Today`s follow」で確認可能です。</p>
    <img src="{{ asset('/img/todayfollow.png') }}" alt="まとめてフォローバッジ">
  </div>

  <div class="p-about__contents" id="about_coin">
    <h3>【２】通貨トレンド</h3>
    <p>仮想通貨名が含まれるツイートを<span>[1時間][1日][1週間]</span>ごとにカウントしました。<br>
    ツイート数から今、どんなコインが話題になっているか？を調べ、取引の参考にしましょう。<br>
    いくつかのコインは、<span>1日の最大取引額/最安取引額</span>も確認可能です。<br>
    </p>
  </div>

  <div class="p-about__contents">
    <h3>【３】仮想通貨ニュース</h3>
    <p>Googleニュースから「仮想通貨」関連の記事を引用し、一覧表示しています。<br>
    「続きを読む」から記事へリンクすることが可能です。
    </p>
  </div>
</section>

<section class="p-about__container">
  <div class="p-about__title">
    <h2>Q.サービスの利用に登録やTwitterアカウントは必須ですか？</h2>
  </div>
  <div class="p-about__contents">
    <p>登録しログインをしていない場合、一部の機能利用に制限があります。<br>
      「仮想通貨ニュース」の利用にはログインは不要ですが、<br>
      「まとめてフォロー」、「通貨トレンド」へのアクセスにはサービスへのログインが必要です。<br>
      また、「まとめてフォロー」で実際にフォロー/自動フォロー機能を利用するには、<br>
      「まとめてフォロー」ページ内でTwitterアカウント認証を行う必要があります。<br>
    <span class ="u-mark__small">※一定時間経過すると、Twitter認証が解除されますので、。</span></p>
  </div>
</section>


<section class="p-about__container">
  <div class="p-about__title">
    <h2>Q.ログインするためのパスワードを忘れました。</h2>
  </div>
  <div class="p-about__contents">
    <p>こちらより再発行が可能です。<br>
    <span class ="u-mark__small">登録時のメールアドレスへ再発行のためのリンクを送信します。</span></p>
  </div>
</section>


<section class="p-about__container">
  <div class="p-about__title">
    <h2>Q.動作環境について教えてください。</h2>
  </div>
  <div class="p-about__contents">
    <p>本サービスの動作確認は下記のブラウザで行っております。<br>
    <span>※2019年10月現在</span></p>
    <ul>
      <li>Mac OS Safari最新</li>
      <li>Mac OS Firefox最新</li>
      <li>Mac OS Google Chrome最新</li>
      <li>iPhone iOS Safari最新</li>
      <li>Internet ExplorerE11/Edge最新<li>
    </ul>
  </div>
</section>


<div class="u-short">
</div>



@endsection

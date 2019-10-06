@extends('layouts.app')

@section('content')

<section class="p-decs__main">
  <div class="p-desc__container">

    <h2 class="p-desc__title c-text">
      About
    </h2>

  </div>
</section>

<section class="p-about__container">
  <div class="p-about__title">
    <h2>本サービスの一部機能について</h2>
  </div>
  <div class="p-about__contents">
    <h3>【１】まとめてフォロー</h3>
    <p>仮想通貨関連のアカウントを抽出し、一覧表示しました。<br>
      ※ユーザーリストは毎日深夜に更新されます。<br>
      Twitterアカウントを登録することで、本サービスから一人一人フォローすることができます。<br>
      <br>

      <span>まとめて画面上の全ユーザーをフォロー</span>する事も可能です。<br>
      まとめてフォローでフォロー可能なアカウント数は14人までとなります。<br>

      <h4>■準備中マークについて</h4>
      <p>まとめてフォローを1度実施すると15分経過するまで再実施はできません。</p><br>
      <img src="{{ asset('/img/badge.png') }}" alt="まとめてフォローバッジ"><br>
      <p>一度実施すると、このようにメニュー部分に緑色のマークが表示されます。<br>
      15分経過するとバッジが消え、再度まとめてフォローを実施可能です。</p>


    <h4>■1日のフォロー数について</h4>
    <p>Twitterは、1日のフォロー数を400人までとして制限をしています。</p>




  </div>
  <div class="p-about__contents">
    <h3>【２】通過トレンド</h3>
    <p>仮想通貨名が含まれるツイートを1時間/1日/1週間ごとにカウントしました。<br>
      今最も呟かれているコイン名、またその時期を取引の参考にしましょう。<br>
      1日の最大取引額/最安取引額を確認可能です。<br>
      <br>
      Twitterアカウントを登録することで、本サービスから一人一人フォローすることができます。
      <span>【まとめてフォロー】</span>を行うと、まとめて画面上の全ユーザーをフォロー可能です。<br>
      ※まとめてフォローでフォロー可能なアカウント数は14人までとなります。<br>
      また、1度実施すると15分経過するまで再実施はできません。</p>
  </div>
  <div class="p-about__contents">
    <h3>【１】まとめてフォロー</h3>
    <p>仮想通貨関連のアカウントを抽出し、一覧表示しました。<br>
      ユーザーリストは毎日深夜に更新されます。<br>
      Twitterアカウントを登録することで、本サービスから一人一人フォローすることができます。
      <span>【まとめてフォロー】</span>を行うと、まとめて画面上の全ユーザーをフォロー可能です。<br>
      ※まとめてフォローでフォロー可能なアカウント数は14人までとなります。<br>
      また、1度実施すると15分経過するまで再実施はできません。</p>
  </div>

</section>

<section class="p-about__container">
  <div class="p-about__title">
    <h2>閲覧環境について</h2>
  </div>
  <div class="p-about__contents">
    <p>本サービスの動作確認は下記環境で行っております。<br>
    <span>※2019年10月現在</span></p>
    <ul>
      <li>Safari最新</li>
      <li>Firefox最新</li>
      <li>Googlechrome最新</li>
      <li>iOS最新</li>
    </ul>
  </div>

</section>



@endsection

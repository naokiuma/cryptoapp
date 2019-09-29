@extends('layouts.app')

@section('content')
<!--グーグルニュース/news-component。コントローラーからlist_gnをjsonにしてvueに渡す-->
<div class="p-desc__container">

  <h2 class="p-desc__title c-text">
    <i class="far fa-newspaper"></i>仮想通貨ニュース一覧
  </h2>
  <p class="p-desc__text c-text">
    Googleニュースより仮想通貨関連のニュースを抜粋しました。
  </p>
</div>

<div class="l-billboard__mini">
  <img alt="ニュース" src="{{ asset('/img/swiper_news.jpg') }}">
</div>


  <div id="newsapp">
      <news-component
      :list_gn="{{ json_encode($list_gn) }}" >
    </news-component>

  </div>


@endsection

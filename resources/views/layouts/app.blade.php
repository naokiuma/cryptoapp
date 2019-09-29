<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>CryptoTrend</title>



    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">
    <!--Iconfonts-->
    <link href="https://use.fontawesome.com/releases/v5.10.2/css/all.css" rel="stylesheet">


    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link href="{{ asset('css/styles.css') }}" rel="stylesheet">




</head>
<body>
  <header class="l-header">
    <div class="p-header__logo">
      <a href="{{ url('/') }}">CryptoTrend</a>
    </div>

    <section class="p-header__navi">
      @guest
      <ul>
        <li><a href="{{ url('register') }}">新規登録</a></li>
        <li><a href="{{ url('login') }}">ログイン</a></li>
      </ul>
      @else
      <ul>
        <li><a href="{{ url('autofollow') }}"><i class="fab fa-twitter"></i>まとめてフォロー</a></li>
        <li><a href="{{ url('coin') }}"><i class="fas fa-coins"></i>通貨トレンド</a></li>
        <li><a href="{{ url('news') }}"><i class="far fa-newspaper"></i>仮想通貨ニュース</a></li>
        <li><a href="{{ url('auth/twitter/logout') }}">ログアウト</a></li>
        <li>{{$user->name}}</li>
        <li><img src="{{$user->avatar}}" class="p-header__icon" alt="ツイッター画像"></li>

<!--元の。9/29修正
        <li><a href="/autofollow"><i class="fab fa-twitter"></i>まとめてフォロー</a></li>
        <li><a href="/coin"><i class="fas fa-coins"></i>通貨トレンド</a></li>
        <li><a href="/news"><i class="far fa-newspaper"></i>仮想通貨ニュース</a></li>
        <li><a href="/auth/twitter/logout">ログアウト</a></li>
        <li>{{$user->name}}</li>
        <li><img src="{{$user->avatar}}" class="p-header__icon" alt="ツイッター画像"></li>
      </ul>
-->

      @endguest
    </section>
  </header>
  <div class="p-header__margin">

  </div>
  <!-- フラッシュメッセージ -->
  @if (session('flash_message'))
      <div class="c-flash__message">
          {{ session('flash_message') }}
      </div>
  @endif


  @yield('content')



  <!-- Scripts -->
  <script src="{{ asset('js/app.js') }}" defer></script>


</body>
</html>

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>@yield('title') | {{ config('app.name') }}</title>
  <meta name="description" content="@yield('description')">
  <meta name="keywords" content="@yield('keywords')">

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
  <div id="wrapper">
    <header class="l-header">
      <div class="p-header__logo">
        <a href="{{ url('/') }}">CryptoTrend</a>
      </div>

      <section class="p-header__navi">

        <div class="p-header__menu">
          @guest
          <ul>
            <li><a href="{{ url('register') }}"><i class="fas fa-play"></i>新規登録</a></li>
            <li><a href="{{ url('login') }}"><i class="fas fa-sign-in-alt"></i>ログイン</a></li>
            <li><a href="{{ url('about') }}">本サービスについて</a></li>
          </ul>
          @else
          <ul>
            @if (session('autofollow'))
            <li><a class="p-twiiter__autofollow" href="{{ url('autofollow') }}"><i class="fab fa-twitter"></i>まとめてフォロー</a></li>
            @else
            <li><a href="{{ url('autofollow') }}"><i class="fab fa-twitter"></i>まとめてフォロー</a></li>
            @endif
            <li><a href="{{ url('coin') }}"><i class="fas fa-coins"></i>通貨トレンド</a></li>
            <li><a href="{{ url('news') }}"><i class="far fa-newspaper"></i>仮想通貨ニュース</a></li>
            <li><a href="{{ url('about') }}">本サービスについて</a></li>
            <li>Today`s follow：{{$user->follow_count}}</li>
            <li><a href="{{ url('auth/twitter/logout') }}"><i class="fas fa-sign-out-alt"></i>ログアウト</a></li>
          </ul>
        </div>

        <div class="p-header__user">
          <ul>
            <li>{{$user->name}}</li>
            @if ($user->handle)<!--もしツイッターアカウントがあれば表示-->
            <li><a href="https://twitter.com/{{$user->handle}}" target="_blank"><img src="{{$user->avatar}}" class="p-header__icon"></a></li>
            @endif
          </ul>

        </div>
        @endguest

      </section>

      <div class="p-spnavi js-spnavi__trigger">
        <span></span>
        <span></span>
        <span></span>
      </div>

      <style>
      .arrow_box {
    	position: relative;
    	background: #ddd6c0;
    	border: 3px solid #414b52;
      width: 200px;
      height: 100px;
    }
    .c-arrow_box_logo{
      color: #ddf8c6;
      text-align: center;
      font-size: 20px;
      line-height: 20px;
      font-weight: bold;
      text-transform: uppercase;
      text-shadow: 0 1px 2px rgba(0, 0, 0, 0.4);

    }
    .arrow_box:after, .arrow_box:before {
    	left: 100%;
    	top: 50%;
    	border: solid transparent;
    	content: " ";
    	height: 0;
    	width: 0;
    	position: absolute;
    	pointer-events: none;
    }

    .arrow_box:after {
    	border-color: rgba(221, 214, 192, 0);
    	border-left-color: #ddd6c0;
    	border-width: 20px;
    	margin-top: -20px;
    }
    .arrow_box:before {
    	border-color: rgba(65, 75, 82, 0);
    	border-left-color: #414b52;
    	border-width: 24px;
    	margin-top: -24px;
    }
      </style>

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


    <footer>
      <div class="l-footernavi__container">
        @guest

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
          <li><a href="{{ url('about') }}">本サービスについて</a></li>
        </ul>

        @else
        <ul>
          <li><a href="{{ url('autofollow') }}"><i class="fab fa-twitter"></i>まとめてフォロー</a></li>
          <li><a href="{{ url('coin') }}"><i class="fas fa-coins"></i>通貨トレンド</a></li>
          <li><a href="{{ url('news') }}"><i class="far fa-newspaper"></i>仮想通貨ニュース</a></li>
        </ul>

        <ul>
          <li><a href="{{ url('about') }}">本サービスについて</a></li>
          <li><a href="{{ url('auth/twitter/logout') }}"><i class="fas fa-sign-out-alt"></i>ログアウト</a></li>
        </ul>

        @endguest
      </div>
    </footer>



    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>

  </div>
</body>
</html>

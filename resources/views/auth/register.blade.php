@extends('layouts.app')
@section('title', '新規登録')
@section('description', '本ページは、CryptoTrendの新規登録Signupページです。新規登録にはメールアドレスが必要です。')
@section('keywords', 'CryptoTrend,Twitter,ツイッター,仮想通貨,暗号通貨,新規登録')

@section('content')

<section class="c-form__container">
  <div class="c-form__title">
    登録フォーム
  </div>
  <div class="c-form__body">
    <form method="POST" action="{{ route('register') }}">
      @csrf

      <div class="c-form__group">
        <label for="name" class="c-form__label">{{ __('Name') }}</label>

        <div>
          <input id="name" type="text" class="c-form__control" name="name" value="{{ old('name') }}" required autocomplete="name" autofocus>

          @error('name')
          <span class="u-error" role="alert">
            <strong>{{ $message }}</strong>
          </span>
          @enderror
        </div>
      </div>

      <div class="c-form__group">
        <label for="email" class="c-form__label">{{ __('E-Mail Address') }}</label>

        <div>
          <input id="email" type="email" class="c-form__control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email">

          @error('email')
          <span class="u-error" role="alert">
            <strong>{{ $message }}</strong>
          </span>
          @enderror
        </div>
      </div>

      <div class="c-form__group">
        <label for="password" class="c-form__label">{{ __('Password') }}</label>※8文字以上

        <div>
          <input id="password" type="password" class="c-form__control @error('password') is-invalid @enderror" name="password" required autocomplete="new-password">

          @error('password')
          <span class="u-error" role="alert">
            <strong>{{ $message }}</strong>
          </span>
          @enderror
        </div>
      </div>

      <div class="c-form__group">
        <label for="password-confirm" class="c-form__label">{{ __('Confirm Password') }}</label>

        <div class="">
          <input id="password-confirm" type="password" class="c-form__control" name="password_confirmation" required autocomplete="new-password">
        </div>
      </div>

      <div class="c-form__group">
        <div>
          <button type="submit" class="c-form__btn">
            {{ __('Register') }}
          </button>
        </div>
      </div>
    </form>
  </div>

  
</section>


@endsection

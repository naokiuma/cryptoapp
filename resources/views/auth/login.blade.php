@extends('layouts.app')

@section('content')



<section class="c-form__container">
  <div class="c-form__title">
    サービスにログインする
  </div>
  <div class="c-form__body">
    <form method="POST" action="{{ route('login') }}">
        @csrf

        <div class="c-form__group">
            <label for="email" class="c-form__label">{{ __('E-Mail Address') }}</label>

            <div>
                <input id="email" type="text" class="c-form__control" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>
                @error('email')
                    <span class="u-error" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>
        </div>

        <div class="c-form__group">
            <label for="password" class="c-form__label">{{ __('Password') }}</label>

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
                  <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                  <label class="c-form__label" for="remember">
                      {{ __('Remember Me') }}
                  </label>
        </div>

        <div class="c-form__group">
                <button type="submit" class="c-form__btn">
                    {{ __('Login') }}
                </button><br>

                @if (Route::has('password.request'))
                    <a href="{{ route('password.request') }}">
                        {{ __('Forgot Your Password?') }}
                    </a>
                @endif
        </div>
      </div>
</section>

@endsection

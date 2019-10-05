@extends('layouts.app')

@section('content')

<div class="c-form__container">
          <div class="c-form__title">
            パスワードをリセットする<br>
            <span class="u-attention">新しいパスワードを設定してください。※6文字以上</span>
          </div>

          <div class="c-form__body">
                @if (session('status'))
                    <div class="alert alert-success" role="alert">
                        {{ session('status') }}
                    </div>
                @endif

                    <form method="POST" action="{{ route('password.update') }}">
                        @csrf
                      <input type="hidden" name="token" value="{{ $token }}">

                      <div class="c-form__group">
                          <label for="email" class="c-form__label">{{ __('E-Mail Address') }}</label>

                          <div>
                              <input id="email" type="email" class="c-form__control" name="email" value="{{ $email ?? old('email') }}" required autocomplete="email" autofocus>
                              <br>
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
                              <input id="password" type="password" class="c-form__control" name="password" required autocomplete="email" autofocus>
                              <br>
                              @error('password')
                                  <span class="u-error" role="alert">
                                      <strong>{{ $message }}</strong>
                                  </span>
                              @enderror
                          </div>
                      </div>

                      <div class="c-form__group">
                          <label for="password-confirm" class="c-form__label">{{ __('Confirm Password') }}</label>

                          <div>
                              <input id="password-confirm" type="password" class="c-form__control" name="password_confirmation" required autocomplete="email" autofocus>

                          </div>
                      </div>

                      <div class="c-form__group">

                              <button type="submit" class="c-form__btn">
                                  {{ __('Reset') }}
                              </button>

                      </div>
                  </form>
                </div>

</div>







@endsection

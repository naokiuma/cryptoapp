@extends('layouts.app')

@section('content')




<div class="c-form__container">
          <div class="c-form__title">
            パスワードをリセットする<br>
            <span class="u-attention">※入力したメールアドレスにリセット用URLを送信します。</span>
          </div>

          <div class="c-form__body">
                @if (session('status'))
                    <div class="alert alert-success" role="alert">
                        {{ session('status') }}
                    </div>
                @endif


                <form method="POST" action="{{ route('password.email') }}">
                    @csrf

                    <div class="c-form__group">
                        <label for="email" class="c-form__label">{{ __('E-Mail Address') }}</label>

                        <div>
                            <input id="email" type="email" class="c-form__control" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>
                            <br>

                            @error('email')
                                <span class="u-error" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>

                    <div class="c-form__group">
                      <button type="submit" class="c-form__btn">
                          {{ __('Send') }}
                      </button><br>
                    </div>
                </form>

          </div>
</div>
@endsection

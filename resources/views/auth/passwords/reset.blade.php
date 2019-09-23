@extends('layouts.app')

@section('content')

<div class="c-form__container">
          <div class="c-form__title">
            パスワードをリセットする<br>
            <span class="u-attention">新しいパスワードを設定してください。※8文字以上</span>
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








<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Reset Password') }}</div>

                <div class="card-body">
                    <form method="POST" action="{{ route('password.update') }}">
                        @csrf

                        <input type="hidden" name="token" value="{{ $token }}">

                        <div class="c-form__group">
                            <label for="email" class="c-form__label">{{ __('E-Mail Address') }}</label>

                            <div>
                                <input id="email" type="email" class="c-form__control" name="email" value="{{ $email ?? old('email') }}" required autocomplete="email" autofocus>
                                <br>

                                @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="c-form__group">
                            <label for="password" class="col-md-4 col-form-label text-md-right">{{ __('Password') }}</label>

                            <div class="col-md-6">
                                <input id="password" type="password" class="c-form__control" name="password" required autocomplete="new-password">
                                <br>

                                @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="c-form__group">
                            <label for="password-confirm" class="col-md-4 col-form-label text-md-right">{{ __('Confirm Password') }}</label>

                            <div class="col-md-6">
                                <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required autocomplete="new-password">
                            </div>
                        </div>

                        <div class="form-group row mb-0">
                            <div class="col-md-6 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    {{ __('Reset Password') }}
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

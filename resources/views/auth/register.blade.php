@extends('layouts.app')

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

              <div class="col-md-6">
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

              <div class="col-md-6">
                  <input id="email" type="email" class="c-form__control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email">

                  @error('email')
                      <span class="u-error" role="alert">
                          <strong>{{ $message }}</strong>
                      </span>
                  @enderror
              </div>
          </div>

          <div class="c-form__group">
              <label for="password" class="c-form__label">{{ __('Password') }}</label>

              <div class="col-md-6">
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
              <label for="twitter" class="c-form__label">
                <input type="checkbox" name="twitter" autocomplete="twitter">
                <i class="fab fa-twitter"></i>{{ __('Use Twitter') }}</label>
                <br>
                <span class="u-attention">「フォロー/まとめてフォロー」をするにはツイッターアカウントが必須です。</span>

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



<!--
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Register') }}</div>

                <div class="card-body">
                    <form method="POST" action="{{ route('register') }}">
                        @csrf

                        <div class="form-group row">
                            <label for="name" class="col-md-4 col-form-label text-md-right">{{ __('Name') }}</label>

                            <div class="col-md-6">
                                <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" required autocomplete="name" autofocus>

                                @error('name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="email" class="col-md-4 col-form-label text-md-right">{{ __('E-Mail Address') }}</label>

                            <div class="col-md-6">
                                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email">

                                @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="password" class="col-md-4 col-form-label text-md-right">{{ __('Password') }}</label>

                            <div class="col-md-6">
                                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="new-password">

                                @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="password-confirm" class="col-md-4 col-form-label text-md-right">{{ __('Confirm Password') }}</label>

                            <div class="col-md-6">
                                <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required autocomplete="new-password">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="twitter" class="col-md-4 col-form-label text-md-right">{{ __('ツイッター認証するだ') }}</label>

                            <div class="col-md-6">
                              <input type="radio" name="twitter" autocomplete="twitter">

                            </div>
                        </div>


                        <div class="form-group row mb-0">
                            <div class="col-md-6 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    {{ __('Register') }}
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
-->
@endsection

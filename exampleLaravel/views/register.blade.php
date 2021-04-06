@extends('layouts.app')

@section('content')
  <section>
    <div class="container-fluid pl-0 pr-0">
      <div class="row no-gutters">
        <div class="col-md-1"></div>
        <div class="col-md-4 p-5 bg-white ">
          <div class="login-main-left">
            <div class="text-center mb-5 login-main-left-header pt-4">
              <img src="{{ asset('img/favicon-61х61.png') }}" class="img-fluid" alt="LOGO">
              <h5 class="mt-3 mb-3">Welcome to xyz</h5>
              <p>{{--It is a long established fact that a reader <br> will be distracted by the readable.--}}</p>
            </div>
            <form method="POST" action="{{ route('register') }}">
              @csrf
              <div class="form-group">
                <label for="name">{{ __('Name') }}</label>
                <input
                    id="name"
                    type="text"
                    class="form-control @error('name') is-invalid @enderror"
                    name="name"
                    value="{{ old('name') }}"
                    required
                    autocomplete="name"
                    autofocus
                    placeholder="Name"
                >
                @error('name')
                <span class="invalid-feedback" role="alert">
                  <strong>{{ $message }}</strong>
                </span>
                @enderror
              </div>
              <div class="form-group">
                <label for="email">{{ __('E-Mail Address') }}</label>
                <input
                    id="email"
                    type="email"
                    class="form-control @error('email') is-invalid @enderror"
                    name="email"
                    value="{{ old('email') }}"
                    required
                    autocomplete="email"
                    autofocus
                    placeholder="E-mail"
                >
                @error('email')
                <span class="invalid-feedback" role="alert">
                  <strong>{{ $message }}</strong>
                </span>
                @enderror
              </div>
              <div class="form-group">
                <label for="password">{{ __('Password') }}</label>
                <i class="fa fa-info-circle fa-lg" title="Must be at least 8 characters in length"></i>
                <input
                    id="password"
                    type="password"
                    class="form-control @error('password') is-invalid @enderror"
                    name="password"
                    required
                    autocomplete="new-password"
                    placeholder="Password"
                >
                @error('password')
                <span class="invalid-feedback" role="alert">
                  <strong>{{ $message }}</strong>
                </span>
                @enderror
              </div>
              <div class="form-group">
                <label for="password-confirm">{{ __('Confirm Password') }}</label>
                <input
                    id="password-confirm"
                    type="password"
                    class="form-control @error('password-confirm') is-invalid @enderror"
                    name="password_confirmation"
                    required
                    autocomplete="new-password"
                    placeholder="Password confirmation"
                >
                @error('password-confirm')
                <span class="invalid-feedback" role="alert">
                  <strong>{{ $message }}</strong>
                </span>
                @enderror
              </div>
              <div class="form-group">
                <label for="promocode">{{ __('Promocode') }}</label>
                <input
                    id="promocode"
                    type="text"
                    class="form-control @error('promocode') is-invalid @enderror"
                    name="promocode"
                    {{--required--}}
                    autocomplete="Promocode"
                    placeholder="Promocode"
                >
                @error('promocode')
                <span class="invalid-feedback" role="alert">
                  <strong>{{ $message }}</strong>
                </span>
                @enderror
              </div>
              <div class="row ml-2">
                <div class="custom-control custom-switch">
                  <input type="checkbox" class="custom-control-input" id="e-trainer" name="e-trainer">
                  <label class="custom-control-label" for="e-trainer">e-trainer</label>
                </div>
                <div class="py-2 text-left">
                  <a href="{{route('terms')}}">Usage of xyz guidelines terms of website</a>
                </div>
                <div class="py-2 text-left">
                  <a href="{{route('faq')}}">Check Tips for using xyz</a>
                </div>
              </div>
              <div class="mt-4">
                <button type="submit" class="btn btn-outline-primary btn-block btn-lg">Sign Up</button>
              </div>
            </form>
            <div class="text-center mt-5">
              <p class="light-gray">Already have an Account? <a href="{{ route('login') }}">Sign In</a>
              </p>
            </div>
          </div>
          <div class="terms text-center mt-5 py-3 px-1 bg-light">
            Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod! Lorem ipsum dolor sit
            amet, consectetur adipiscing elit,
            Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod sed do eiusmod Lorem
            ipsum dolor sit amet, consectetur adipiscing elit
          </div>
        </div>
        <div class="col-md-7 carousel-bg">
          <div class="login-main-right bg-white p-5 mt-5 mb-5">
            <div class="owl-carousel owl-carousel-login">
              <div class="item">
                <div class="carousel-login-card text-center">
                  <img src="img/login/carousel3.jpg" class="img-fluid" alt="LOGO">
                  <h5 class="mt-5 mb-3">Lorem ipsum dolor</h5>
                  <p class="mb-4 mx-2">Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do
                    eiusmod! Lorem ipsum dolor sit amet, consectetur adipiscing elit, Lorem ipsum
                    dolor sit amet, consectetur adipiscing elit, sed do eiusmod sed do eiusmod Lorem
                    ipsum dolor sit amet, consectetur adipiscing elit</p>
                </div>
              </div>
              <div class="item">
                <div class="carousel-login-card text-center">
                  <img src="img/login/carousel5.jpg" class="img-fluid" alt="LOGO">
                  <h5 class="mt-5 mb-3">Lorem ipsum dolor</h5>
                  <p class="mb-4 mx-2">Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do
                    eiusmod! Lorem ipsum dolor sit amet, consectetur adipiscing elit, Lorem ipsum
                    dolor sit amet, consectetur adipiscing elit, sed do eiusmod sed do eiusmod Lorem
                    ipsum dolor sit amet, consectetur adipiscing elit</p>
                </div>
              </div>
              <div class="item">
                <div class="carousel-login-card text-center">
                  <img src="img/login/carousel9.jpg" class="img-fluid" alt="LOGO">
                  <h5 class="mt-5 mb-3">Lorem ipsum dolor</h5>
                  <p class="mb-4 mx-2">Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do
                    eiusmod! Lorem ipsum dolor sit amet, consectetur adipiscing elit, Lorem ipsum
                    dolor sit amet, consectetur adipiscing elit, sed do eiusmod sed do eiusmod Lorem
                    ipsum dolor sit amet, consectetur adipiscing elit</p>
                </div>
              </div>
              <div class="item">
                <div class="carousel-login-card text-center">
                  <img src="img/login/carousel7.jpg" class="img-fluid" alt="LOGO">
                  <h5 class="mt-5 mb-3">Lorem ipsum dolor</h5>
                  <p class="mb-4 mx-2">Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do
                    eiusmod! Lorem ipsum dolor sit amet, consectetur adipiscing elit, Lorem ipsum
                    dolor sit amet, consectetur adipiscing elit, sed do eiusmod sed do eiusmod Lorem
                    ipsum dolor sit amet, consectetur adipiscing elit</p>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
@endsection

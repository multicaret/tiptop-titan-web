@extends('layouts.admin-master')
@section('title', __('Login'))
@section('container')
    <div class="authentication-wrapper authentication-2 ui-bg-cover ui-bg-overlay-container px-4"
         style="background-image: url('/images/auth-bg.jpg');">
        <div class="ui-bg-overlay bg-dark opacity-25"></div>

        <div class="authentication-inner py-5">

            <div class="card">
                <div class="p-4 p-sm-5">
                    <!-- Logo -->
                    <div class="d-flex justify-content-center align-items-center pb-2 mb-4">
                        <div class="ui-w-60">
                            <img src="/images/logo.svg" class="w-100 h-100">
                        </div>
                    </div>
                    <!-- / Logo -->

                    <h5 class="text-center text-muted font-weight-normal mb-4">Login to Your Account</h5>

                <!-- Form -->
                    <form class="form-horizontal form-material" id="loginform" method="POST"
                          action="{{ localization()->localizeURL(route('login')) }}">
                        @csrf
                        <div class="form-group">
                            <label for="login" class="form-label">{{__('Email Address')}}</label>
                            <input id="login" type="email" placeholder="example@domain.com"
                                   class="form-control{{ $errors->has('login') ? ' is-invalid' : '' }}" name="login"
                                   value="{{ old('login') }}" required autofocus dir="ltr">

                            @if ($errors->has('login'))
                                <span class="invalid-feedback">
                                    <strong>{{ $errors->first('login') }}</strong>
                                </span>
                            @endif
                        </div>
                        <div class="form-group">
                            <label for="password" class="form-label d-flex justify-content-between align-items-end">
                                {{__('Password')}}
                            </label>
                            <input id="password" type="password"
                                   class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}"
                                   name="password" required dir="ltr">

                            @if ($errors->has('password'))
                                <span class="invalid-feedback">
                                    <strong>{{ $errors->first('password') }}</strong>
                                </span>
                            @endif
                        </div>
                        <div class="d-flex justify-content-between align-items-center m-0">
                            <label class="custom-control custom-checkbox m-0">
                                <input type="checkbox" class="custom-control-input" name="remember">
                                <span class="custom-control-label">{{__('Remember me')}}</span>
                            </label>
                            <button type="submit" class="btn btn-primary">{{__('Log In')}}</button>
                        </div>
                    </form>
                    <!-- / Form -->
                </div>
            </div>

        </div>
    </div>
@endsection

@push('styles')
    <style>
        .authentication-wrapper {
            display: -ms-flexbox;
            display: flex;
            -ms-flex-preferred-size: 100%;
            flex-basis: 100%;
            min-height: 100vh;
            width: 100%
        }

        .authentication-wrapper .authentication-inner {
            width: 100%
        }

        .authentication-wrapper.authentication-1, .authentication-wrapper.authentication-2, .authentication-wrapper.authentication-4 {
            -ms-flex-align: center;
            align-items: center;
            -ms-flex-pack: center;
            justify-content: center
        }

        .authentication-wrapper.authentication-1 .authentication-inner {
            max-width: 300px
        }

        .authentication-wrapper.authentication-2 .authentication-inner {
            max-width: 380px
        }

        .authentication-wrapper.authentication-3 {
            -ms-flex-align: stretch;
            align-items: stretch;
            -ms-flex-pack: stretch;
            justify-content: stretch
        }

        .authentication-wrapper.authentication-3 .authentication-inner {
            display: -ms-flexbox;
            display: flex;
            -ms-flex-align: stretch;
            align-items: stretch;
            -ms-flex-wrap: nowrap;
            flex-wrap: nowrap;
            -ms-flex-pack: stretch;
            justify-content: stretch
        }

        .authentication-wrapper.authentication-4 .authentication-inner {
            max-width: 800px
        }

        @media all and (-ms-high-contrast: none), (-ms-high-contrast: active) {
            .authentication-wrapper::after {
                content: '';
                display: block;
                -ms-flex: 0 0 0%;
                flex: 0 0 0%;
                min-height: inherit;
                width: 0;
                font-size: 0
            }
        }
    </style>
@endpush

@extends('layouts.auth')

@section('title', 'Login')

@section('content')
<div class="auth-wrapper">
    <div class="auth__left col-12 col-md-5">
        <div class="auth__left-logo libre">RuPo</div>
        <div class="auth__left-decor">
            <span></span><span></span><span></span>
        </div>
    </div>

    <div class="auth__right col-12 col-md-7">
        <form class="auth__form" method="POST" action="{{ route('login') }}">
            @csrf

            <h2 class="libre">LOGIN</h2>

            @if (session('status'))
                <small class="text-success d-block mb-2">{{ session('status') }}</small>
            @endif

            <input class="auth__input" type="email" name="email" value="{{ old('email') }}" placeholder="Email" required autocomplete="username" autofocus>
            @error('email')<small class="text-danger d-block mb-2">{{ $message }}</small>@enderror

            <input class="auth__input" type="password" name="password" placeholder="Password" required autocomplete="current-password">
            @error('password')<small class="text-danger d-block mb-2">{{ $message }}</small>@enderror

            <label class="d-flex align-items-center gap-2 mb-3">
                <input type="checkbox" name="remember">
                <small>Remember me</small>
            </label>

            <button class="auth__btn" type="submit">Login</button>

            <div class="auth__divider">or</div>

            <small>
                Still not registered?
                <a href="{{ route('register') }}">Registration</a>
            </small>
        </form>
    </div>
</div>
@endsection

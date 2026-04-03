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
        <div class="auth__form">
            <h2 class="libre">LOGIN</h2>

            <input class="auth__input" type="email" placeholder="Email">
            <input class="auth__input" type="password" placeholder="Password">

            <button class="auth__btn">Login</button>

            <div class="auth__divider">or</div>

            <small>
                Still not registered?
                <a href="{{ route('register') }}">Registration</a>
            </small>
        </div>
    </div>

</div>
@endsection

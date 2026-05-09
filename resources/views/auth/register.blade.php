@extends('layouts.auth')

@section('title', 'Registration')

@section('content')
<div class="register-page">
    <div class="register-card">
        <form class="auth__form" id="reg-form" method="POST" action="{{ route('register') }}">
            @csrf

            <h2 class="libre">Registration</h2>
            <p class="subtitle">Create your account</p>

            <div class="row g-2">
                <div class="col-12 col-sm-6">
                    <input class="auth__input" id="inp-name" type="text" name="name" value="{{ old('name') }}" placeholder="Name" required autocomplete="name" autofocus>
                    @error('name')<small class="text-danger d-block mb-2">{{ $message }}</small>@enderror
                </div>
                <div class="col-12 col-sm-6">
                    <input class="auth__input" id="inp-surname" type="text" name="surname" value="{{ old('surname') }}" placeholder="Surname" required autocomplete="family-name">
                    @error('surname')<small class="text-danger d-block mb-2">{{ $message }}</small>@enderror
                </div>
            </div>

            <input class="auth__input" id="inp-email" type="email" name="email" value="{{ old('email', request('email')) }}" placeholder="Email" required autocomplete="username">
            @error('email')<small class="text-danger d-block mb-2">{{ $message }}</small>@enderror

            <input class="auth__input" id="inp-password" type="password" name="password" placeholder="Password" required autocomplete="new-password">
            @error('password')<small class="text-danger d-block mb-2">{{ $message }}</small>@enderror

            <input class="auth__input" type="password" name="password_confirmation" placeholder="Confirm password" required autocomplete="new-password">

            <select class="auth__select" id="inp-type" name="user_type" required>
                <option value="" disabled {{ old('user_type') ? '' : 'selected' }}>Type of user</option>
                <option value="seller" {{ old('user_type') === 'seller' ? 'selected' : '' }}>Seller</option>
                <option value="buyer" {{ old('user_type') === 'buyer' ? 'selected' : '' }}>Buyer</option>
            </select>
            @error('user_type')<small class="text-danger d-block mb-2">{{ $message }}</small>@enderror

            <button class="auth__btn" type="submit">Register</button>

            <div class="auth__divider">or</div>

            <small>Already have an account? <a href="{{ route('login') }}">Login</a></small>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script>
    // Preserve old UX: highlight invalid fields before submit.
    document.getElementById('reg-form').addEventListener('submit', (e) => {
        const fields = [
            { id: 'inp-name', check: v => v.trim().length > 0 },
            { id: 'inp-surname', check: v => v.trim().length > 0 },
            { id: 'inp-email', check: v => /\S+@\S+\.\S+/.test(v) },
            { id: 'inp-password', check: v => v.length >= 8 },
            { id: 'inp-type', check: v => v !== '' },
        ];

        let valid = true;

        fields.forEach(({ id, check }) => {
            const input = document.getElementById(id);
            const isOk = check(input.value);
            input.style.boxShadow = isOk ? '' : '0 0 0 3px rgba(224,85,85,0.35)';
            if (!isOk) valid = false;
        });

        if (!valid) e.preventDefault();
    });
</script>
@endsection


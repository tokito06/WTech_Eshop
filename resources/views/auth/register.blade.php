@extends('layouts.auth')

@section('title', 'Registration')

@section('content')
<div class="register-page">
    <div class="register-card">
        <div class="auth__form" id="reg-form">

            <h2 class="libre">Registration</h2>
            <p class="subtitle">Create your account</p>

            <div class="row g-2">
                <div class="col-12 col-sm-6">
                    <input class="auth__input" id="inp-name" type="text" placeholder="Name">
                    <span class="field-hint" id="hint-name">Enter your name</span>
                </div>
                <div class="col-12 col-sm-6">
                    <input class="auth__input" id="inp-surname" type="text" placeholder="Surname">
                    <span class="field-hint" id="hint-surname">Enter your surname</span>
                </div>
            </div>

            <input class="auth__input" id="inp-email" type="email" placeholder="Email">
            <span class="field-hint" id="hint-email">Enter a valid email</span>

            <input class="auth__input" id="inp-password" type="password" placeholder="Password">
            <span class="field-hint" id="hint-password">Minimum 6 characters</span>

            <select class="auth__select" id="inp-type">
                <option value="">Type of user</option>
                <option value="seller">Seller</option>
                <option value="buyer">Buyer</option>
            </select>
            <span class="field-hint" id="hint-type">Please select a user type</span>

            <button class="auth__btn" id="reg-btn">Register</button>

            <div class="auth__divider">or</div>

            <small>Already have an account? <a href="{{ route('login') }}">Login</a></small>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.getElementById('reg-btn').addEventListener('click', () => {
        const fields = [
            { id: 'inp-name',     hint: 'hint-name',     check: v => v.trim().length > 0 },
            { id: 'inp-surname',  hint: 'hint-surname',  check: v => v.trim().length > 0 },
            { id: 'inp-email',    hint: 'hint-email',    check: v => /\S+@\S+\.\S+/.test(v) },
            { id: 'inp-password', hint: 'hint-password', check: v => v.length >= 6 },
            { id: 'inp-type',     hint: 'hint-type',     check: v => v !== '' },
        ];

        let valid = true;

        fields.forEach(({ id, hint, check }) => {
            const input  = document.getElementById(id);
            const hintEl = document.getElementById(hint);
            const isOk   = check(input.value);
            hintEl.classList.toggle('visible', !isOk);
            input.style.boxShadow = isOk ? '' : '0 0 0 3px rgba(224,85,85,0.35)';
            if (!isOk) valid = false;
        });

        if (valid) alert('Registration successful!');
    });

    ['inp-name','inp-surname','inp-email','inp-password','inp-type'].forEach(id => {
        document.getElementById(id).addEventListener('input', () => {
            const hint = document.getElementById('hint-' + id.replace('inp-',''));
            if (hint) hint.classList.remove('visible');
            document.getElementById(id).style.boxShadow = '';
        });
    });
</script>
@endsection

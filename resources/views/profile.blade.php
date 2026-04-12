@extends('layouts.app')

@section('title', 'Profile')

@section('extra-css')
<link rel="stylesheet" href="{{ asset('css/profile.css') }}">
@endsection

@section('content')
<main class="profile-section">
    <div class="container">
        <div class="profile-card">
            <div class="row g-4 g-md-5 align-items-start">

                <!-- Avatar + Logout -->
                <div class="col-12 col-md-5">
                    <div class="avatar-wrap">
                        <div class="avatar">
                            <span class="material-symbols-outlined">account_circle</span>
                        </div>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button class="btn-logout" type="submit" onclick="return confirm('Are you sure you want to log out?')">Log out?</button>
                        </form>
                    </div>
                </div>

                <div class="col-divider d-md-none"></div>

                <!-- Info + Edit -->
                <div class="col-12 col-md-7">
                    <div class="profile-info">
                        <form id="profile-form" method="POST" action="{{ route('profile.update') }}">
                            @csrf
                            @method('PATCH')

                            <div class="info-block">
                                <div class="field-group">
                                    <span class="field-label">Full Name</span>
                                    <input class="info-field" id="field-name" name="name" type="text" value="{{ old('name', auth()->user()->name) }}" readonly required>
                                    @error('name')<small class="text-danger d-block mt-1">{{ $message }}</small>@enderror
                                </div>
                                <div class="field-group">
                                    <span class="field-label">Email</span>
                                    <input class="info-field" id="field-email" name="email" type="email" value="{{ old('email', auth()->user()->email) }}" readonly required>
                                    @error('email')<small class="text-danger d-block mt-1">{{ $message }}</small>@enderror
                                </div>
                                <div class="field-group">
                                    <span class="field-label">Phone</span>
                                    <input class="info-field" id="field-phone" name="phone" type="tel" value="{{ old('phone', auth()->user()->phone ?? '') }}" readonly required>
                                </div>
                            </div>
                            <button class="btn-edit" id="btn-edit" type="button">Edit</button>
                        </form>
                    </div>
                </div>

            </div>
        </div>
    </div>
</main>

<!-- Toast -->
<div class="toast-container position-fixed bottom-0 end-0 p-3" style="z-index:9999">
    <div id="profileToast" class="toast align-items-center border-0" role="alert" aria-live="assertive">
        <div class="d-flex align-items-center gap-2 p-3" style="background:var(--highlight-color); border-radius:10px;">
            <span class="material-symbols-outlined">check_circle</span>
            <span id="toast-msg">Profile saved!</span>
            <button type="button" class="btn-close ms-auto" data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    const editBtn   = document.getElementById('btn-edit');
    const fields    = document.querySelectorAll('.info-field');
    const profileForm = document.getElementById('profile-form');
    let editing = false;

    function showToast(msg) {
        document.getElementById('toast-msg').textContent = msg;
        bootstrap.Toast.getOrCreateInstance(document.getElementById('profileToast'), { delay: 2500 }).show();
    }

    editBtn.addEventListener('click', () => {
        editing = !editing;
        fields.forEach(f => {
            if (f.name === 'name' || f.name === 'email' || f.name === 'phone') {
                editing ? f.removeAttribute('readonly') : f.setAttribute('readonly', '');
            }
        });

        if (editing) {
            document.getElementById('field-name').focus();
        } else {
            profileForm.submit();
        }

        editBtn.textContent = editing ? 'Save' : 'Edit';
        editBtn.style.backgroundColor = editing ? 'var(--highlight-dark-color)' : '';
    });

    @if (session('status') === 'profile-updated')
    showToast('Profile saved!');
    @endif
</script>
@endsection

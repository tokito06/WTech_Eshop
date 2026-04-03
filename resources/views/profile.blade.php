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
                        <button class="btn-logout" id="btn-logout">Log out?</button>
                    </div>
                </div>

                <div class="col-divider d-md-none"></div>

                <!-- Info + Edit -->
                <div class="col-12 col-md-7">
                    <div class="profile-info">
                        <div class="info-block">
                            <div class="field-group">
                                <span class="field-label">Full Name</span>
                                <input class="info-field" id="field-name" type="text" value="name , td" readonly>
                            </div>
                            <div class="field-group">
                                <span class="field-label">Email</span>
                                <input class="info-field" id="field-email" type="email" value="example@email.com" readonly>
                            </div>
                            <div class="field-group">
                                <span class="field-label">Phone</span>
                                <input class="info-field" id="field-phone" type="tel" value="+421 xx xxx xx xx" readonly>
                            </div>
                        </div>
                        <button class="btn-edit" id="btn-edit">Edit</button>
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
    const logoutBtn = document.getElementById('btn-logout');
    let editing = false;

    function showToast(msg) {
        document.getElementById('toast-msg').textContent = msg;
        bootstrap.Toast.getOrCreateInstance(document.getElementById('profileToast'), { delay: 2500 }).show();
    }

    editBtn.addEventListener('click', () => {
        editing = !editing;
        fields.forEach(f => editing ? f.removeAttribute('readonly') : f.setAttribute('readonly', ''));
        if (editing) fields[0].focus();
        editBtn.textContent = editing ? 'Save' : 'Edit';
        editBtn.style.backgroundColor = editing ? 'var(--highlight-dark-color)' : '';
        if (!editing) showToast('Profile saved!');
    });

    logoutBtn.addEventListener('click', () => {
        if (confirm('Are you sure you want to log out?')) showToast('Logged out successfully');
    });
</script>
@endsection

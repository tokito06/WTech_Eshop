@extends('layouts.app')

@section('title', 'Profile')

@section('extra-css')
<link rel="stylesheet" href="{{ asset('css/profile.css') }}">
@endsection

@section('content')
<main class="profile-page">
    <div class="container">
        <section class="profile-hero">
            <div class="profile-hero__identity">
                <div class="profile-avatar" aria-hidden="true">
                    <span class="material-symbols-outlined">account_circle</span>
                </div>

                <div class="profile-hero__meta">
                    <p class="profile-hero__eyebrow">Account center</p>
                    <h1 class="profile-hero__title">{{ auth()->user()->name }}</h1>
                    <p class="profile-hero__subtitle">{{ auth()->user()->email }}</p>
                    <div class="profile-hero__chips">
                        <span class="profile-chip">
                            <span class="material-symbols-outlined">verified_user</span>
                            Trusted customer
                        </span>
                        <span class="profile-chip">
                            <span class="material-symbols-outlined">calendar_month</span>
                            Since {{ auth()->user()->created_at?->format('M Y') }}
                        </span>
                    </div>
                </div>
            </div>

            <div class="profile-hero__actions">
                @if(auth()->user()->hasAdminAccess())
                    <a href="{{ route('admin.products') }}" class="btn-admin-panel">
                        <span class="material-symbols-outlined">admin_panel_settings</span>
                        Admin panel
                    </a>
                @endif
                <div>
                    <button 
                        class="btn-logout"
                        type="button"
                        data-bs-toggle="modal"
                        data-bs-target="#logoutConfirmModal"
                    >
                        <span class="material-symbols-outlined">logout</span>
                        Log out
                    </button>
                </div>
            </div>
        </section>

        <section class="quick-actions" aria-label="Profile shortcuts">
            <a href="{{ route('cart') }}" class="quick-card">
                <span class="material-symbols-outlined quick-card__icon">shopping_bag</span>
                <h2 class="quick-card__title">My cart</h2>
                <p class="quick-card__text">Check products, quantities, and total before checkout.</p>
            </a>

            <a href="{{ route('favourites') }}" class="quick-card">
                <span class="material-symbols-outlined quick-card__icon">favorite</span>
                <h2 class="quick-card__title">Favourites</h2>
                <p class="quick-card__text">View saved items and move the best picks into your cart.</p>
            </a>

            <a href="{{ route('delivery') }}" class="quick-card">
                <span class="material-symbols-outlined quick-card__icon">local_shipping</span>
                <h2 class="quick-card__title">Delivery details</h2>
                <p class="quick-card__text">Manage address and shipping preferences for future orders.</p>
            </a>

            <a href="{{ route('payment') }}" class="quick-card">
                <span class="material-symbols-outlined quick-card__icon">credit_card</span>
                <h2 class="quick-card__title">Payment</h2>
                <p class="quick-card__text">Review payment method and complete checkout in one step.</p>
            </a>

            <a href="{{ route('shop') }}" class="quick-card">
                <span class="material-symbols-outlined quick-card__icon">storefront</span>
                <h2 class="quick-card__title">Continue shopping</h2>
                <p class="quick-card__text">Explore categories and discover new products in the catalog.</p>
            </a>

            <a href="#profile-details" class="quick-card">
                <span class="material-symbols-outlined quick-card__icon">settings</span>
                <h2 class="quick-card__title">Account settings</h2>
                <p class="quick-card__text">Update your name, email, and phone in the details section.</p>
            </a>
        </section>

        <section class="profile-details" id="profile-details">
            <div class="profile-details__head">
                <h2>Personal information</h2>
                <p>Keep your account information up to date for smooth checkout and support.</p>
            </div>

            <form id="profile-form" method="POST" action="{{ route('profile.update') }}" class="profile-form">
                @csrf
                @method('PATCH')

                <div class="profile-fields">
                    <label class="field-group" for="field-name">
                        <span class="field-label">Full name</span>
                        <input class="info-field" id="field-name" name="name" type="text" value="{{ old('name', auth()->user()->name) }}" readonly required>
                        @error('name')<small class="text-danger d-block mt-1">{{ $message }}</small>@enderror
                    </label>

                    <label class="field-group" for="field-email">
                        <span class="field-label">Email</span>
                        <input class="info-field" id="field-email" name="email" type="email" value="{{ old('email', auth()->user()->email) }}" readonly required>
                        @error('email')<small class="text-danger d-block mt-1">{{ $message }}</small>@enderror
                    </label>

                    <label class="field-group" for="field-phone">
                        <span class="field-label">Phone</span>
                        <input class="info-field" id="field-phone" name="phone" type="tel" value="{{ old('phone', auth()->user()->phone ?? '') }}" readonly required>
                    </label>
                </div>

                <button class="btn-edit" id="btn-edit" type="button">Edit profile</button>
            </form>
        </section>
    </div>
</main>

<x-pop-out-modal
    modal-id="logoutConfirmModal"
    title="Are you sure?"
    message="Are you sure you want to log out? You will need to sign in again to access your profile."
    action="{{ route('logout') }}"
    confirm-text="Log Out"
    confirm-class="btn btn-danger"
/>

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
    const editBtn = document.getElementById('btn-edit');
    const fields = document.querySelectorAll('.info-field');
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

        editBtn.textContent = editing ? 'Save changes' : 'Edit profile';
        editBtn.classList.toggle('btn-edit--active', editing);
    });

    @if (session('status') === 'profile-updated')
    showToast('Profile saved!');
    @endif
</script>
@endsection

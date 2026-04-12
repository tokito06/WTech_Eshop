<?php

test('forgot password endpoint is unavailable', function () {
    $this->get('/forgot-password')->assertNotFound();
    $this->post('/forgot-password', [
        'email' => 'user@example.com',
    ])->assertNotFound();
});

test('reset password endpoint is unavailable', function () {
    $this->get('/reset-password/token')->assertNotFound();
    $this->post('/reset-password', [
        'token' => 'token',
        'email' => 'user@example.com',
        'password' => 'password',
        'password_confirmation' => 'password',
    ])->assertNotFound();
});

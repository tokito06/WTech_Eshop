<?php

test('registration screen can be rendered', function () {
    $response = $this->get('/register');

    $response->assertStatus(200);
});

test('new users can register', function () {
    $response = $this->post('/register', [
        'name' => 'Test User',
        'surname' => 'Tester',
        'user_type' => 'user',
        'email' => 'test@example.com',
        'password' => 'Password123!',
        'password_confirmation' => 'Password123!',
    ]);

    $this->assertAuthenticated();
    $response->assertRedirect(route('home', absolute: false));
});

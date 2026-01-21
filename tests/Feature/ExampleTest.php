<?php

use App\Models\User;

it('redirects authenticated users to movies index', function () {
    // Arrange
    $user = User::factory()->create();

    // Act
    $response = $this->actingAs($user)->get('/');

    // Assert - Espera redirecionamento para movies.index
    $response->assertStatus(302); // Redirecionamento
    $response->assertRedirect(route('movies.index'));
});

it('redirects unauthenticated users to login', function () {
    // Act
    $response = $this->get('/');

    // Assert - Espera redirecionamento para login
    $response->assertStatus(302); // Redirecionamento
    $response->assertRedirect('/login');
});

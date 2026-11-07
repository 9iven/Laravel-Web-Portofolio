<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('app layout renders theme initialization script and dark mode classes', function () {
    $response = $this->get(route('home'));

    $response->assertStatus(200);
    $response->assertSee('localStorage.theme === \'dark\'', false);
    $response->assertSee('document.documentElement.classList.add(\'dark\')', false);
    $response->assertSee('dark:bg-gray-900', false);
    $response->assertSee('dark:text-gray-100', false);
    $response->assertSee('aria-label="Toggle dark mode"', false);
});

test('guest layout for login renders theme initialization script and dark classes', function () {
    $response = $this->get(route('login'));

    $response->assertStatus(200);
    $response->assertSee('localStorage.theme === \'dark\'', false);
    $response->assertSee('dark:bg-gray-900', false);
    $response->assertSee('dark:bg-gray-800', false);
});

test('public pages contain dark mode support classes', function (string $route) {
    $response = $this->get(route($route));

    $response->assertStatus(200);
    $response->assertSee('dark:', false);
})->with(['home', 'about', 'education', 'projects']);

test('authenticated post pages contain dark mode support classes', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->get(route('posts.index'));

    $response->assertStatus(200);
    $response->assertSee('dark:', false);
});

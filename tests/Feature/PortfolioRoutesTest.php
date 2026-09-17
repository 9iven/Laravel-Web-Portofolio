<?php

test('home route returns status 200 and loads correct view', function () {
    $response = $this->get('/');
    $response->assertStatus(200);
    $response->assertSee('Given Gerald Chandra');
    $response->assertSee('Mahasiswa Teknologi Rekayasa Perangkat Lunak');
});

test('about route returns status 200 and renders profile', function () {
    $response = $this->get('/about');
    $response->assertStatus(200);
    $response->assertSee('Given Gerald Chandra');
    $response->assertSee('25/560235/SV/26410');
    $response->assertSee('B2');
});

test('education route returns status 200 and renders education history', function () {
    $response = $this->get('/education');
    $response->assertStatus(200);
    $response->assertSee('Universitas Gadjah Mada');
    $response->assertSee('Teknologi Rekayasa Perangkat Lunak');
});

test('projects route returns status 200 and renders project cards', function () {
    $response = $this->get('/projects');
    $response->assertStatus(200);
    $response->assertSee('reCRUD');
    $response->assertSee('Website Portofolio');
});

test('demo halo-dunia returns status 200 and direct string response', function () {
    $response = $this->get('/demo/halo-dunia');
    $response->assertStatus(200);
    $response->assertSee('Halo dunia dari Route Laravel!');
});

test('demo halo with required parameter returns greeting', function () {
    $response = $this->get('/demo/halo/Given');
    $response->assertStatus(200);
    $response->assertSee('Halo Given');
});

test('demo halo with optional parameter returns default value', function () {
    $response = $this->get('/demo/halo');
    $response->assertStatus(200);
    $response->assertSee('Halo Tanpa Nama');
});

test('demo passing-data renders variables correctly', function () {
    $response = $this->get('/demo/passing-data');
    $response->assertStatus(200);
    $response->assertSee('Given Gerald Chandra');
    $response->assertSee('Pemrograman Web Lanjutan');
});

test('demo redirect redirects to destination', function () {
    $response = $this->get('/demo/lama');
    $response->assertRedirect('/demo/baru');
});

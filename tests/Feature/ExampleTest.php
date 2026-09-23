<?php

test('the application returns a successful response', function () {
    $response = $this->get('/');

    $response->assertStatus(200);
});

test('home product search accepts the search query param', function () {
    $response = $this->get('/products?search=tomato');

    $response->assertStatus(200);
});

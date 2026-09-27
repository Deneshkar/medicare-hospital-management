<?php

test('guests visiting the homepage are redirected to login', function () {
    $response = $this->get('/');

    $response->assertRedirect(route('login'));
});

<?php

it('redirects the apex domain to the first tenant homepage', function () {
    $response = $this->get('http://resourcedb.me/');

    $response->assertRedirect('http://first.resourcedb.me');
});

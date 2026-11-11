<?php

use Sendio\ValueObjects\Transporter\BaseUri;

it('can be created from a string', function () {
    $baseUri = BaseUri::from('api.send.io');

    expect($baseUri->toString())->toBe('https://api.send.io/');
});

it('can be created with a protocol', function () {
    $baseUri = BaseUri::from('http://api.sendio.test');

    expect($baseUri->toString())->toBe('http://api.sendio.test/');
});

it('can be created with a trailing slash', function () {
    $baseUri = BaseUri::from('https://api.sendio.run/');

    expect($baseUri->toString())->toBe('https://api.sendio.run/');
});

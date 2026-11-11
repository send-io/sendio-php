<?php

use Sendio\Service\ApiKey;

test('service is created when required through property', function () {
    $sendio = Sendio::client('foo');

    expect($sendio->apiKeys)
        ->toBeInstanceOf(ApiKey::class);
});

test('sevice is created when required through method', function () {
    $sendio = Sendio::client('foo');

    expect($sendio->apiKeys())
        ->toBeInstanceOf(ApiKey::class);
});

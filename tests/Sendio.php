<?php

use Sendio\Client;

afterEach(function () {
    putenv('SENDIO_BASE_URL');
});

function transporterBaseUri(Client $client): string
{
    $transporter = (new ReflectionProperty(Client::class, 'transporter'))->getValue($client);
    $baseUri = (new ReflectionProperty($transporter, 'baseUri'))->getValue($transporter);

    return $baseUri->toString();
}

it('can create a client', function () {
    $sendio = Sendio::client('foo');

    expect($sendio)->toBeInstanceOf(Client::class)
        ->and(transporterBaseUri($sendio))->toBe('https://api.send.io/');
});

it('can create a client when the SENDIO_BASE_URL environment variable is set', function () {
    putenv('SENDIO_BASE_URL=https://api.sendio.run/');

    $sendio = Sendio::client('foo');

    expect($sendio)->toBeInstanceOf(Client::class)
        ->and(transporterBaseUri($sendio))->toBe('https://api.sendio.run/');
});

it('accepts a base URL in code, which wins over SENDIO_BASE_URL', function () {
    putenv('SENDIO_BASE_URL=https://ignored.example.com');

    $sendio = Sendio::client('foo', 'http://localhost:8080');

    expect(transporterBaseUri($sendio))->toBe('http://localhost:8080/');
});

it('resolves the base URL from argument, environment, then default', function () {
    expect(Sendio::resolveBaseUrl())->toBe('https://api.send.io');

    putenv('SENDIO_BASE_URL=https://api.sendio.run');
    expect(Sendio::resolveBaseUrl())->toBe('https://api.sendio.run')
        ->and(Sendio::resolveBaseUrl('https://custom.example.com'))->toBe('https://custom.example.com')
        ->and(Sendio::resolveBaseUrl(''))->toBe('https://api.sendio.run');
});

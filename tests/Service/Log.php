<?php

use Sendio\Collection;
use Sendio\Log;

it('can get a log by id', function () {
    $client = mockClient('GET', 'logs/37e4414c-5e25-4dbc-a071-43552a4bd53b', [], [], sendioLog());

    $result = $client->logs->get('37e4414c-5e25-4dbc-a071-43552a4bd53b');

    expect($result)->toBeInstanceOf(Log::class)
        ->id->toBe('37e4414c-5e25-4dbc-a071-43552a4bd53b');
});

it('can get a list of logs', function () {
    $client = mockClient('GET', 'logs', [], [], sendioLogs());

    $result = $client->logs->list();

    expect($result)->toBeInstanceOf(Collection::class)
        ->data->toBeArray();
});

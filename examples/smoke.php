<?php

/**
 * Live smoke test against a real send.io endpoint. Not part of the unit tests.
 *
 *   composer install
 *   SENDIO_API_KEY=mx_... SENDIO_BASE_URL=https://api.send.io php examples/smoke.php
 *
 * Optional: SMOKE_FROM (default ada@compat-sdk.sendio-test.dev), SMOKE_TO (default delivered@send.io).
 */

require __DIR__ . '/../vendor/autoload.php';

$apiKey = getenv('SENDIO_API_KEY');
if (! is_string($apiKey) || $apiKey === '') {
    fwrite(STDERR, "SENDIO_API_KEY is not set\n");
    exit(2);
}

// The base URL comes from SENDIO_BASE_URL; it can also be passed as the second argument.
$sendio = Sendio::client($apiKey);
$from = getenv('SMOKE_FROM') ?: 'ada@compat-sdk.sendio-test.dev';
$to = getenv('SMOKE_TO') ?: 'delivered@send.io';

echo 'base URL: ' . Sendio::resolveBaseUrl() . "\n";

$failed = false;

try {
    $sent = $sendio->emails->send([
        'from' => $from,
        'to' => [$to],
        'subject' => 'sendio-php ' . Sendio::VERSION . ' smoke test',
        'html' => '<p>Sent by the send.io PHP SDK smoke test.</p>',
        'text' => 'Sent by the send.io PHP SDK smoke test.',
    ]);
    echo "send: OK id={$sent->id}\n";

    $email = $sendio->emails->get($sent->id);
    echo "get: OK id={$email->id} last_event=" . ($email->last_event ?? '?') . "\n";
} catch (Throwable $e) {
    $failed = true;
    echo 'send/get: FAIL ' . get_class($e) . ': ' . $e->getMessage() . "\n";
}

try {
    $domains = $sendio->domains->list();
    echo 'domains.list: OK count=' . count($domains->data) . "\n";
} catch (Throwable $e) {
    $failed = true;
    echo 'domains.list: FAIL ' . get_class($e) . ': ' . $e->getMessage() . "\n";
}

try {
    $keys = $sendio->apiKeys->list();
    echo 'apiKeys.list: OK count=' . count($keys->data) . "\n";
} catch (Throwable $e) {
    $failed = true;
    echo 'apiKeys.list: FAIL ' . get_class($e) . ': ' . $e->getMessage() . "\n";
}

exit($failed ? 1 : 0);

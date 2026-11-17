<?php

use Sendio\Collection;
use Sendio\Email;
use Sendio\TransactionalEmail;

$txId = 'tx_2hX9Yq1bVv0kq3W4m8sZJt1aB7c';

it('can create a transactional email with an idempotency key', function () {
    $parameters = [
        'name' => 'Sign-in code',
        'template_id' => 'signin',
        'from' => 'Acme <security@acme.com>',
        'subject' => 'Your code is {{CODE}}',
        'track_clicks' => true,
    ];
    $client = mockClient('POST', 'transactional', $parameters, ['Idempotency-Key' => 'tx-1'], transactionalEmail(['track_clicks' => true]));

    $result = $client->transactional->create($parameters, ['idempotency_key' => 'tx-1']);

    expect($result)->toBeInstanceOf(TransactionalEmail::class)
        ->id->toBe('tx_2hX9Yq1bVv0kq3W4m8sZJt1aB7c')
        ->published_version_number->toBe(3)
        ->track_clicks->toBeTrue();
});

it('can list transactional emails by search and template', function () {
    $client = mockClient('GET', 'transactional?limit=5&search=code&template_id=signin', [], [], [
        'object' => 'list',
        'has_more' => false,
        'data' => [transactionalEmail()],
    ]);

    $result = $client->transactional->list(['limit' => 5, 'search' => 'code', 'template_id' => 'signin', 'ignored' => 'x']);

    expect($result)->toBeInstanceOf(Collection::class)
        ->data->toHaveCount(1)
        ->and($result->data[0])->toBeInstanceOf(TransactionalEmail::class);
});

it('can get a transactional email with its variables', function () use ($txId) {
    $client = mockClient('GET', "transactional/{$txId}", [], [], transactionalEmail([
        'variables' => [['key' => 'CODE', 'type' => 'string', 'fallback_value' => null]],
    ]));

    $result = $client->transactional->get($txId);

    expect($result->variables[0]['key'])->toBe('CODE');
});

it('can update a transactional email with expected_updated_at', function () use ($txId) {
    $parameters = ['subject' => 'New', 'preview_text' => null, 'expected_updated_at' => '2026-11-16T09:05:00Z'];
    $client = mockClient('PATCH', "transactional/{$txId}", $parameters, [], transactionalEmail(['subject' => 'New']));

    $result = $client->transactional->update($txId, $parameters);

    expect($result)->subject->toBe('New');
});

it('can remove a transactional email', function () use ($txId) {
    $client = mockClient('DELETE', "transactional/{$txId}", [], [], ['object' => 'transactional_email', 'id' => $txId, 'deleted' => true]);

    expect($client->transactional->remove($txId))->deleted->toBeTrue();
});

it('can send a test of a transactional email', function () use ($txId) {
    $client = mockClient('POST', "transactional/{$txId}/test", ['to' => ['me@acme.com'], 'variables' => ['CODE' => '1']], [], ['id' => 'e1']);

    $result = $client->transactional->test($txId, 'me@acme.com', ['CODE' => '1']);

    expect($result)->toBeInstanceOf(Email::class)->id->toBe('e1');
});

it('can read the stats of a transactional email', function () use ($txId) {
    $client = mockClient('GET', "transactional/{$txId}/stats?from=2026-11-01&granularity=hourly", [], [], [
        'object' => 'transactional_email_stats',
        'transactional_id' => $txId,
        'totals' => ['sent' => 3],
    ]);

    $result = $client->transactional->stats($txId, ['from' => '2026-11-01', 'granularity' => 'hourly']);

    expect($result->totals['sent'])->toBe(3);
});

it('sends a transactional email by id', function () use ($txId) {
    $parameters = ['transactional_id' => $txId, 'to' => 'ada@example.com', 'variables' => ['CODE' => '123456']];
    $client = mockClient('POST', 'emails', $parameters, [], ['id' => 'e1']);

    expect($client->emails->send($parameters))->id->toBe('e1');
});

it('sends per-send tracking with a transactional email', function () use ($txId) {
    $parameters = ['transactional_id' => $txId, 'to' => 'ada@example.com', 'track_opens' => false, 'track_clicks' => false];
    $client = mockClient('POST', 'emails', $parameters, [], ['id' => 'e1']);

    expect($client->emails->send($parameters))->id->toBe('e1');
});

it('creates a broadcast from a published template version', function () {
    $parameters = ['name' => 'Launch', 'segment_id' => 's1', 'template_id' => 'launch', 'template_version_id' => 'v1'];
    $client = mockClient('POST', 'broadcasts', $parameters, [], ['id' => 'b1']);

    expect($client->broadcasts->create($parameters))->id->toBe('b1');
});

it('detaches a broadcast from its template with template_id null', function () {
    $client = mockClient('PATCH', 'broadcasts/b1', ['template_id' => null], [], ['id' => 'b1']);

    expect($client->broadcasts->update('b1', ['template_id' => null]))->id->toBe('b1');
});

it('switches a broadcast to another version of its template', function () {
    $client = mockClient('PATCH', 'broadcasts/b1', ['template_version_id' => 'v2'], [], ['id' => 'b1']);

    expect($client->broadcasts->update('b1', ['template_version_id' => 'v2']))->id->toBe('b1');
});

it('reads the template link and unfillable variables of a broadcast', function () {
    $client = mockClient('GET', 'broadcasts/b1', [], [], [
        'object' => 'broadcast',
        'id' => 'b1',
        'template_id' => 't1',
        'template_version_id' => 'v1',
        'template_name' => 'Launch',
        'template_version_number' => 2,
        'template_version_name' => 'Final',
        'template_deleted' => false,
        'unfillable_variables' => ['RESET_URL'],
    ]);

    $result = $client->broadcasts->get('b1');

    expect($result)
        ->template_id->toBe('t1')
        ->template_version_number->toBe(2)
        ->template_deleted->toBeFalse()
        ->unfillable_variables->toBe(['RESET_URL']);
});

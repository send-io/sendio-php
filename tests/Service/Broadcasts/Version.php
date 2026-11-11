<?php

use Sendio\Broadcasts\Version;
use Sendio\Collection;

it('can list a broadcast\'s versions', function () {
    $client = mockClient('GET', 'broadcasts/559ac32e-9ef5-46fb-82a1-b76b840c0f7b/versions?limit=10&after=v_1&before=v_3', [], [], broadcastVersions());

    $result = $client->broadcasts->versions->list('559ac32e-9ef5-46fb-82a1-b76b840c0f7b', [
        'limit' => 10,
        'after' => 'v_1',
        'before' => 'v_3',
    ]);

    expect($result)->toBeInstanceOf(Collection::class)
        ->has_more->toBeFalse();

    expect($result->data[0])->toBeInstanceOf(Version::class)
        ->version_number->toBe(2)
        ->current->toBeTrue();
});

it('can get a broadcast version by number', function () {
    $client = mockClient('GET', 'broadcasts/559ac32e-9ef5-46fb-82a1-b76b840c0f7b/versions/2', [], [], broadcastVersion());

    expect($client->broadcasts->versions->get('559ac32e-9ef5-46fb-82a1-b76b840c0f7b', 2))
        ->toBeInstanceOf(Version::class)
        ->version_number->toBe(2)
        ->tracking->toBeFalse();
});

it('can label a broadcast version', function () {
    $client = mockClient('PATCH', 'broadcasts/559ac32e-9ef5-46fb-82a1-b76b840c0f7b/versions/2', [
        'name' => 'Final',
        'comment' => 'Approved',
    ], [], broadcastVersion());

    expect($client->broadcasts->versions->update('559ac32e-9ef5-46fb-82a1-b76b840c0f7b', 2, [
        'name' => 'Final',
        'comment' => 'Approved',
    ]))->toBeInstanceOf(Version::class);
});

it('can restore a broadcast version', function () {
    $client = mockClient('POST', 'broadcasts/559ac32e-9ef5-46fb-82a1-b76b840c0f7b/versions/2/restore', [], [], array_merge(broadcastVersion(), [
        'version_number' => 3,
        'restored_from' => 2,
    ]));

    expect($client->broadcasts->versions->restore('559ac32e-9ef5-46fb-82a1-b76b840c0f7b', 2))
        ->version_number->toBe(3)
        ->restored_from->toBe(2);
});

<?php

use Sendio\Broadcast;
use Sendio\Broadcasts\Estimate;
use Sendio\Broadcasts\Recipient;
use Sendio\Broadcasts\Stats;
use Sendio\Client;
use Sendio\Collection;
use Sendio\Contracts\Transporter;
use Sendio\Exceptions\ErrorException;

it('can get a broadcase resource', function () {
    $client = mockClient('GET', 'broadcasts/559ac32e-9ef5-46fb-82a1-b76b840c0f7b', [], [], broadcast());

    $result = $client->broadcasts->get('559ac32e-9ef5-46fb-82a1-b76b840c0f7b');

    expect($result)->toBeInstanceOf(Broadcast::class)
        ->id->toBe('559ac32e-9ef5-46fb-82a1-b76b840c0f7b');
});

it('can create a broadcast resource', function () {
    $client = mockClient('POST', 'broadcasts', [
        'audience_id' => '78261eea-8f8b-4381-83c6-79fa7120f1cf',
        'from' => 'Acme <onboarding@send.io>',
        'subject' => 'hello world',
        'html' => 'Hi {{{FIRST_NAME|there}}}, you can unsubscribe here: {{{SENDIO_UNSUBSCRIBE_URL}}}',
    ], [], broadcast());

    $result = $client->broadcasts->create([
        'audience_id' => '78261eea-8f8b-4381-83c6-79fa7120f1cf',
        'from' => 'Acme <onboarding@send.io>',
        'subject' => 'hello world',
        'html' => 'Hi {{{FIRST_NAME|there}}}, you can unsubscribe here: {{{SENDIO_UNSUBSCRIBE_URL}}}',
    ]);

    expect($result)->toBeInstanceOf(Broadcast::class)
        ->id->toBe('559ac32e-9ef5-46fb-82a1-b76b840c0f7b');
});

it('can update a broadcast resource', function () {
    $client = mockClient('PATCH', 'broadcasts/559ac32e-9ef5-46fb-82a1-b76b840c0f7b', [], [], broadcast());

    $result = $client->broadcasts->update('559ac32e-9ef5-46fb-82a1-b76b840c0f7b', []);

    expect($result)->toBeInstanceOf(Broadcast::class)
        ->id->toBe('559ac32e-9ef5-46fb-82a1-b76b840c0f7b');
});

it('can get a list of broadcast resources', function () {
    $client = mockClient('GET', 'broadcasts', [], [], broadcasts());

    $result = $client->broadcasts->list();

    expect($result)->toBeInstanceOf(Collection::class)
        ->data->toBeArray();
});

it('can get a list of broadcast recipients', function () {
    $client = mockClient('GET', 'broadcasts/559ac32e-9ef5-46fb-82a1-b76b840c0f7b/recipients?type=clicked&limit=20', [], [], broadcastRecipients());

    $result = $client->broadcasts->recipients('559ac32e-9ef5-46fb-82a1-b76b840c0f7b', [
        'type' => 'clicked',
        'limit' => 20,
    ]);

    expect($result)->toBeInstanceOf(Collection::class)
        ->has_more->toBeTrue();

    expect($result->data[0])->toBeInstanceOf(Recipient::class)
        ->id->toBe('b2Zmc2V0OjA')
        ->contact_id->toBe('e169aa45-1ecf-4183-9955-b1499d5701d3')
        ->email->toBe('carter@example.com')
        ->count->toBe(3)
        ->clicked_links->toBe([
            ['url' => 'https://send.io/pricing', 'clicks' => 2],
        ]);
});

it('can get a list of bounced broadcast recipients filtered by bounce type', function () {
    $client = mockClient('GET', 'broadcasts/559ac32e-9ef5-46fb-82a1-b76b840c0f7b/recipients?type=bounced&bounce_type=permanent', [], [], broadcastBouncedRecipients());

    $result = $client->broadcasts->recipients('559ac32e-9ef5-46fb-82a1-b76b840c0f7b', [
        'type' => 'bounced',
        'bounce_type' => 'permanent',
    ]);

    expect($result)->toBeInstanceOf(Collection::class)
        ->has_more->toBeFalse();

    expect($result->data[0])->toBeInstanceOf(Recipient::class)
        ->id->toBe('b2Zmc2V0OjE')
        ->contact_id->toBeNull()
        ->email->toBe('dana@example.com')
        ->bounce_type->toBe('permanent');
});

it('cannot get recipients for a broadcast that does not exist', function () {
    /** @var Mockery\MockInterface|Transporter $transporter */
    $transporter = Mockery::mock(Transporter::class);
    $transporter->shouldReceive('request')->once()->andThrow(new ErrorException([
        'statusCode' => 404,
        'name' => 'not_found',
        'message' => 'Broadcast not found',
    ]));

    $client = new Client($transporter);

    $client->broadcasts->recipients('559ac32e-9ef5-46fb-82a1-b76b840c0f7b', [
        'type' => 'sent',
    ]);
})->throws(ErrorException::class, 'Broadcast not found');

it('can send a broadcast resource', function () {
    $client = mockClient('POST', 'broadcasts/559ac32e-9ef5-46fb-82a1-b76b840c0f7b/send', [
        'scheduled_at' => 'in 1 min',
    ], [], broadcast());

    $result = $client->broadcasts->send('559ac32e-9ef5-46fb-82a1-b76b840c0f7b', [
        'scheduled_at' => 'in 1 min',
    ]);

    expect($result)->toBeInstanceOf(Broadcast::class)
        ->id->toBe('559ac32e-9ef5-46fb-82a1-b76b840c0f7b');
});

it('can cancel a broadcast resource', function () {
    $client = mockClient('POST', 'broadcasts/559ac32e-9ef5-46fb-82a1-b76b840c0f7b/cancel', [], [], broadcast());

    $result = $client->broadcasts->cancel('559ac32e-9ef5-46fb-82a1-b76b840c0f7b');

    expect($result)->toBeInstanceOf(Broadcast::class)
        ->id->toBe('559ac32e-9ef5-46fb-82a1-b76b840c0f7b');
});

it('can duplicate a broadcast resource', function () {
    $client = mockClient('POST', 'broadcasts/559ac32e-9ef5-46fb-82a1-b76b840c0f7b/duplicate', [], [], [
        'id' => '3d4a472d-bc6d-4dd2-aa9d-4a0a4e5f6b7c',
        'object' => 'broadcast',
    ]);

    $result = $client->broadcasts->duplicate('559ac32e-9ef5-46fb-82a1-b76b840c0f7b');

    expect($result)->toBeInstanceOf(Broadcast::class)
        ->id->toBe('3d4a472d-bc6d-4dd2-aa9d-4a0a4e5f6b7c');
});

it('can remove a broadcast resource', function () {
    $client = mockClient('DELETE', 'broadcasts/559ac32e-9ef5-46fb-82a1-b76b840c0f7b', [], [], broadcast());

    $result = $client->broadcasts->remove('559ac32e-9ef5-46fb-82a1-b76b840c0f7b');

    expect($result)->toBeInstanceOf(Broadcast::class)
        ->id->toBe('559ac32e-9ef5-46fb-82a1-b76b840c0f7b');
});

it('sends every create field, tracking false included', function () {
    $parameters = [
        'name' => 'Launch',
        'from' => 'Acme <a@acme.com>',
        'subject' => 'Hi',
        'reply_to' => ['r@acme.com'],
        'html' => '<p>Hi</p>',
        'text' => 'Hi',
        'preview_text' => 'Preview',
        'topic_id' => 'b6d24b8e-af0b-4c3c-be0c-359bbd97381e',
        'segment_id' => '78261eea-8f8b-4381-83c6-79fa7120f1cf',
        'segment_ids' => ['78261eea-8f8b-4381-83c6-79fa7120f1cf', 'a1b2c3d4-0000-4000-8000-000000000002'],
        'exclude_segment_ids' => ['a1b2c3d4-0000-4000-8000-000000000003'],
        'audience' => 'segments',
        'unsubscribe_scope' => 'list',
        'tracking' => false,
        'template_engine' => 'handlebars',
        'send' => true,
        'scheduled_at' => 'in 1 hour',
    ];
    $client = mockClient('POST', 'broadcasts', $parameters, [], broadcast());

    $result = $client->broadcasts->create($parameters);

    expect($result)->toBeInstanceOf(Broadcast::class);
});

it('can create an empty draft broadcast', function () {
    $client = mockClient('POST', 'broadcasts', [], [], ['object' => 'broadcast', 'id' => 'b_1']);

    expect($client->broadcasts->create([]))->id->toBe('b_1');
});

it('sends update fields without the broadcast id in the body', function () {
    $client = mockClient('PATCH', 'broadcasts/559ac32e-9ef5-46fb-82a1-b76b840c0f7b', [
        'name' => 'Renamed',
        'tracking' => false,
        'topic_id' => null,
        'segment_ids' => [],
        'audience' => 'all',
        'unsubscribe_scope' => 'all',
        'template_engine' => 'simple',
        'preview_text' => 'Preview',
    ], [], ['object' => 'broadcast', 'id' => '559ac32e-9ef5-46fb-82a1-b76b840c0f7b']);

    $result = $client->broadcasts->update('559ac32e-9ef5-46fb-82a1-b76b840c0f7b', [
        'name' => 'Renamed',
        'tracking' => false,
        'topic_id' => null,
        'segment_ids' => [],
        'audience' => 'all',
        'unsubscribe_scope' => 'all',
        'template_engine' => 'simple',
        'preview_text' => 'Preview',
    ]);

    expect($result)->id->toBe('559ac32e-9ef5-46fb-82a1-b76b840c0f7b');
});

it('can send a broadcast without parameters', function () {
    $client = mockClient('POST', 'broadcasts/559ac32e-9ef5-46fb-82a1-b76b840c0f7b/send', [], [], ['id' => '559ac32e-9ef5-46fb-82a1-b76b840c0f7b']);

    expect($client->broadcasts->send('559ac32e-9ef5-46fb-82a1-b76b840c0f7b'))
        ->id->toBe('559ac32e-9ef5-46fb-82a1-b76b840c0f7b');
});

it('passes the broadcast list filters', function () {
    $client = mockClient('GET', 'broadcasts?limit=5&after=cursor1&status=draft&segment_id=78261eea-8f8b-4381-83c6-79fa7120f1cf&search=launch+week', [], [], broadcasts());

    $result = $client->broadcasts->list([
        'limit' => 5,
        'after' => 'cursor1',
        'status' => 'draft',
        'segment_id' => '78261eea-8f8b-4381-83c6-79fa7120f1cf',
        'search' => 'launch week',
        'unknown' => 'dropped',
    ]);

    expect($result)->toBeInstanceOf(Collection::class);
});

it('reads the new broadcast fields', function () {
    $client = mockClient('GET', 'broadcasts/559ac32e-9ef5-46fb-82a1-b76b840c0f7b', [], [], array_merge(broadcast(), [
        'name' => null,
        'segment_id' => null,
        'audience_id' => null,
        'segment_ids' => [],
        'exclude_segment_ids' => ['a1b2c3d4-0000-4000-8000-000000000003'],
        'audience' => 'all',
        'unsubscribe_scope' => 'list',
        'tracking' => false,
        'template_engine' => 'handlebars',
        'topic_id' => null,
        'version_number' => 4,
    ]));

    expect($client->broadcasts->get('559ac32e-9ef5-46fb-82a1-b76b840c0f7b'))
        ->name->toBeNull()
        ->segment_id->toBeNull()
        ->audience_id->toBeNull()
        ->segment_ids->toBe([])
        ->exclude_segment_ids->toBe(['a1b2c3d4-0000-4000-8000-000000000003'])
        ->audience->toBe('all')
        ->unsubscribe_scope->toBe('list')
        ->tracking->toBeFalse()
        ->template_engine->toBe('handlebars')
        ->topic_id->toBeNull()
        ->preview_text->toBe('Check out our latest announcements')
        ->version_number->toBe(4);
});

it('can estimate a broadcast audience', function () {
    $parameters = [
        'segment_id' => '78261eea-8f8b-4381-83c6-79fa7120f1cf',
        'segment_ids' => ['a1b2c3d4-0000-4000-8000-000000000002'],
        'exclude_segment_ids' => ['a1b2c3d4-0000-4000-8000-000000000003'],
        'audience' => 'segments',
        'topic_id' => 'b6d24b8e-af0b-4c3c-be0c-359bbd97381e',
    ];
    $client = mockClient('POST', 'broadcasts/estimate', $parameters, [], [
        'object' => 'broadcast_estimate',
        'recipients' => 120,
        'skipped' => ['invalid' => 3],
    ]);

    expect($client->broadcasts->estimate($parameters))
        ->toBeInstanceOf(Estimate::class)
        ->object->toBe('broadcast_estimate')
        ->recipients->toBe(120)
        ->skipped->toBe(['invalid' => 3]);
});

it('can send a broadcast test', function () {
    $client = mockClient('POST', 'broadcasts/559ac32e-9ef5-46fb-82a1-b76b840c0f7b/test', [
        'to' => ['a@example.com', 'b@example.com'],
    ], [], ['object' => 'broadcast_test', 'sent' => 2]);

    expect($client->broadcasts->test('559ac32e-9ef5-46fb-82a1-b76b840c0f7b', ['a@example.com', 'b@example.com']))
        ->object->toBe('broadcast_test')
        ->sent->toBe(2);
});

it('wraps a single broadcast test address in a list', function () {
    $client = mockClient('POST', 'broadcasts/559ac32e-9ef5-46fb-82a1-b76b840c0f7b/test', [
        'to' => ['a@example.com'],
    ], [], ['object' => 'broadcast_test', 'sent' => 1]);

    expect($client->broadcasts->test('559ac32e-9ef5-46fb-82a1-b76b840c0f7b', 'a@example.com'))
        ->sent->toBe(1);
});

it('can get a broadcast\'s stats', function () {
    $client = mockClient('GET', 'broadcasts/559ac32e-9ef5-46fb-82a1-b76b840c0f7b/stats', [], [], [
        'recipients' => 10,
        'delivered' => 9,
        'open_rate' => 0.5,
        'skipped' => ['invalid' => 1],
    ]);

    expect($client->broadcasts->stats('559ac32e-9ef5-46fb-82a1-b76b840c0f7b'))
        ->toBeInstanceOf(Stats::class)
        ->delivered->toBe(9)
        ->open_rate->toBe(0.5)
        ->skipped->toBe(['invalid' => 1]);
});

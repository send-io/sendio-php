<?php

use Sendio\Automation;
use Sendio\Collection;

it('can get an automation by id', function () {
    $client = mockClient('GET', 'automations/c9b16d4f-ba6c-4e2e-b044-6bf4404e57fd', [], [], automation());

    $result = $client->automations->get('c9b16d4f-ba6c-4e2e-b044-6bf4404e57fd');

    expect($result)->toBeInstanceOf(Automation::class)
        ->id->toBe('c9b16d4f-ba6c-4e2e-b044-6bf4404e57fd');
});

it('can create an automation', function () {
    $client = mockClient('POST', 'automations', [
        'name' => 'Welcome series',
        'status' => 'disabled',
        'steps' => [
            [
                'key' => 'trigger',
                'type' => 'trigger',
                'config' => ['event_name' => 'user.created'],
            ],
        ],
        'connections' => [],
    ], [], automation());

    $result = $client->automations->create([
        'name' => 'Welcome series',
        'status' => 'disabled',
        'steps' => [
            [
                'key' => 'trigger',
                'type' => 'trigger',
                'config' => ['event_name' => 'user.created'],
            ],
        ],
        'connections' => [],
    ]);

    expect($result)->toBeInstanceOf(Automation::class)
        ->id->toBe('c9b16d4f-ba6c-4e2e-b044-6bf4404e57fd');
});

it('can get a list of automations', function () {
    $client = mockClient('GET', 'automations', [], [], automations());

    $result = $client->automations->list();

    expect($result)->toBeInstanceOf(Collection::class)
        ->data->toBeArray();
});

it('can get a list of automations filtered by status', function () {
    $client = mockClient('GET', 'automations?status=enabled', [], [], automations());

    $result = $client->automations->list(['status' => 'enabled']);

    expect($result)->toBeInstanceOf(Collection::class)
        ->data->toBeArray();
});

it('can update an automation', function () {
    $client = mockClient('PATCH', 'automations/c9b16d4f-ba6c-4e2e-b044-6bf4404e57fd', [
        'status' => 'enabled',
    ], [], automation());

    $result = $client->automations->update('c9b16d4f-ba6c-4e2e-b044-6bf4404e57fd', [
        'status' => 'enabled',
    ]);

    expect($result)->toBeInstanceOf(Automation::class)
        ->id->toBe('c9b16d4f-ba6c-4e2e-b044-6bf4404e57fd');
});

it('can remove an automation', function () {
    $client = mockClient('DELETE', 'automations/c9b16d4f-ba6c-4e2e-b044-6bf4404e57fd', [], [], automation());

    $result = $client->automations->remove('c9b16d4f-ba6c-4e2e-b044-6bf4404e57fd');

    expect($result)->toBeInstanceOf(Automation::class)
        ->id->toBe('c9b16d4f-ba6c-4e2e-b044-6bf4404e57fd');
});

it('can duplicate an automation', function () {
    $client = mockClient('POST', 'automations/c9b16d4f-ba6c-4e2e-b044-6bf4404e57fd/duplicate', [], [], [
        'object' => 'automation',
        'id' => 'e169aa45-1ecf-4183-9955-b1499d5701d3',
    ]);

    $result = $client->automations->duplicate('c9b16d4f-ba6c-4e2e-b044-6bf4404e57fd');

    expect($result)->toBeInstanceOf(Automation::class)
        ->id->toBe('e169aa45-1ecf-4183-9955-b1499d5701d3');
});

it('can stop an automation', function () {
    $client = mockClient('POST', 'automations/c9b16d4f-ba6c-4e2e-b044-6bf4404e57fd/stop', [], [], automation());

    $result = $client->automations->stop('c9b16d4f-ba6c-4e2e-b044-6bf4404e57fd');

    expect($result)->toBeInstanceOf(Automation::class)
        ->id->toBe('c9b16d4f-ba6c-4e2e-b044-6bf4404e57fd');
});

it('can create an automation from a template', function () {
    $parameters = [
        'slug' => 'welcome-series',
        'version' => 1,
        'name' => 'My welcome series',
        'events' => [
            'signup' => ['mode' => 'create', 'name' => 'user.created'],
        ],
        'sender' => ['from' => 'Acme <hello@example.com>', 'reply_to' => 'support@example.com'],
        'topic_id' => 'b6d24b8e-af0b-4c3c-be0c-359bbd97381e',
        'params' => ['follow_up_delay' => '3d'],
    ];

    $client = mockClient('POST', 'automations/from-template', $parameters, [], automationFromTemplate());

    $result = $client->automations->createFromTemplate($parameters);

    expect($result)->toBeInstanceOf(Automation::class)
        ->id->toBe('c9b16d4f-ba6c-4e2e-b044-6bf4404e57fd')
        ->status->toBe('disabled')
        ->source_template->toBe(['slug' => 'welcome-series', 'version' => 1, 'name' => 'Welcome series']);
});

it('can create an automation from a template with an idempotency key', function () {
    $parameters = [
        'slug' => 'welcome-series',
        'sender' => ['from' => 'Acme <hello@example.com>'],
    ];

    $client = mockClient('POST', 'automations/from-template', $parameters, [
        'Idempotency-Key' => 'unique-key',
    ], automationFromTemplate());

    $result = $client->automations->createFromTemplate($parameters, ['idempotency_key' => 'unique-key']);

    expect($result)->toBeInstanceOf(Automation::class)
        ->id->toBe('c9b16d4f-ba6c-4e2e-b044-6bf4404e57fd');
});

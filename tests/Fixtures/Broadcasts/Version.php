<?php

function broadcastVersion(): array
{
    return [
        'object' => 'broadcast_version',
        'id' => 'f0e1d2c3-0000-4000-8000-000000000002',
        'broadcast_id' => '559ac32e-9ef5-46fb-82a1-b76b840c0f7b',
        'version_number' => 2,
        'name' => null,
        'comment' => null,
        'broadcast_name' => 'Announcements',
        'subject' => 'hello world',
        'restored_from' => null,
        'created_at' => '2024-12-01 19:32:22.98+00',
        'current' => true,
        'from' => 'Acme <onboarding@send.io>',
        'tracking' => false,
        'template_engine' => 'simple',
        'segment_ids' => ['78261eea-8f8b-4381-83c6-79fa7120f1cf'],
        'exclude_segment_ids' => [],
        'audience' => 'segments',
        'unsubscribe_scope' => 'all',
    ];
}

function broadcastVersions(): array
{
    return [
        'object' => 'list',
        'has_more' => false,
        'data' => [
            [
                'object' => 'broadcast_version',
                'id' => 'f0e1d2c3-0000-4000-8000-000000000002',
                'broadcast_id' => '559ac32e-9ef5-46fb-82a1-b76b840c0f7b',
                'version_number' => 2,
                'current' => true,
                'created_at' => '2024-12-01 19:32:22.98+00',
            ],
        ],
    ];
}

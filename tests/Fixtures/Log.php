<?php

function sendioLog(): array
{
    return [
        'object' => 'log',
        'id' => '37e4414c-5e25-4dbc-a071-43552a4bd53b',
        'created_at' => '2026-03-30 13:43:54.622865+00',
        'endpoint' => '/emails',
        'method' => 'POST',
        'response_status' => 200,
        'user_agent' => 'sendio-node:1.0.0',
        'request_body' => [
            'from' => 'Acme <onboarding@send.io>',
            'to' => ['delivered@send.io'],
            'subject' => 'Hello World',
        ],
        'response_body' => [
            'id' => '4ef9a417-02e9-4d39-ad75-9611e0fcc33c',
        ],
    ];
}

function sendioLogs(): array
{
    return [
        'data' => [
            [
                'id' => '37e4414c-5e25-4dbc-a071-43552a4bd53b',
                'created_at' => '2026-03-30 13:43:54.622865+00',
                'endpoint' => '/emails',
                'method' => 'POST',
                'response_status' => 200,
                'user_agent' => 'sendio-php:1.0.0',
            ],
        ],
    ];
}
